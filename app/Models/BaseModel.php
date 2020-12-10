<?php


namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function list(array $select, array $conditions, array $orderBy = array('id', 'desc'), bool $paginate = true, int $page = 1, $pageSize = 10) : array
    {
        $this->autoConditions($conditions)
             ->autoOrderBy($orderBy)
             ->select($select);

        if ($paginate) {
            $requestPage = request('page');
            $currentPage = $requestPage ? $requestPage : $page;
            $res = $this->paginate($pageSize, ['*'], 'page', $currentPage);
//            $res = $result->toSql();dd($res);
        } else {
            $res = $this->get();
        }

        if (count($res)) {
            return resultToArray($res);
        } else {
            return [];
        }
    }

    public function add(array $fields)
    {
        return self::create($fields);
    }

    public function updateById(int $id, array $fields)
    {
        return self::where('id', $id)
            ->update($fields);
    }

    public function deleteById(int $id)
    {
        return self::where('id', $id)
            ->update(['delete_time' => time()]);
    }

    public function autoOrderBy(array $orderBy)
    {
        $count = count($orderBy);
        $i = 0;
        while (($count - $i) >= 2) {
            $this->orderBy($orderBy[$i], $orderBy[$i + 1]);
            $i += 2;
        }

        return $this;
    }

    public function autoConditions(array $conditions)
    {
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $num = count($condition);
                if ($num != 2 && $num != 3) {
                    throw new \Exception('conditions传值异常');
                }
                if ($num == 3) {
                    switch (strtolower($condition[1])) {
                        case 'in':
                            $this->whereIn($condition[0], $condition[2]);
                            break;
                        case 'notin':
                            $this->whereNotIn($condition[0], $condition[2]);
                            break;
                        case 'like':
                            $this->where($condition[0], $condition[1], $condition[2]);
                            break;
                        case 'find_in_set':
                            $this->whereRaw("FIND_IN_SET({$condition[2]}, {$condition[0]})");
                            break;
                        default:
                            $this->where($condition[0], $condition[1], $condition[2]);
                    }
                } else {
                    $this->where($condition[0], $condition[1]);
                }
            }
        }

        return $this;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
