<?php


namespace App\DB;


class MySQL
{
    /**
     * @var false|\mysqli
     */
    private $connection = null;

    /**
     * @var string
     */
    private $fields = '*';

    /**
     * @var bool|string
     */
    private $where = false;

    /**
     * @var bool|string
     */
    private $orderBy = false;

    /**
     * @var string
     */
    private $direction = 'asc';

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var array
     */
    public $newData;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    public $table;

    public function __construct()
    {
        $config = include('app/config.php');
        $this->connection = mysqli_connect($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['database']);
        if (false === $this->connection) {
            throw new \Exception('Can not connect to DB');
        }

        foreach (['create', 'check'] as $job) {
            if (!$this->tableExists()) {
                switch ($job) {
                    case "create":
                        $this->createTable();
                        break;

                    case "check":
                        throw new \Exception('Can Not create table');
                        break;
                }
            }
        }
    }

    public function tableExists(): bool
    {
        $tables = current($this->connection->query('show tables')->fetch_all());

        return in_array($this->table, $tables ?: []);
    }

    public function getConnection(): \mysqli
    {
        return $this->connection;
    }

    public function get(): array
    {
        if (!$this->table) {
            return [];
        }
        $query = implode(" ", array_filter(["select %s from %s", $this->hasWhere(), $this->hasOrderBy(), "limit %d,%d"], [$this, 'filterOutEmpty']));
        $params = array_filter([
            $this->fields,
            $this->table,
            $this->getWhere(),
            $this->getOrderBy(),
            $this->page(),
            $this->limit(),
        ], [$this, 'filterOutEmpty']);

        $query = vsprintf($query, $params);
        return $this->connection->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @return array|bool|\mysqli_result
     */
    public function save()
    {
        if (!$this->table) {
            return [];
        }
        $query = sprintf("insert into %s (%s) values(%s)", $this->table, implode(",", array_keys($this->newData)), "'" . implode("','", $this->newData) . "'");
        return $this->connection->query($query);
    }

    public function delete($id): \mysqli_result
    {
        $query = "delete from {$this->table} where id='$id'";
        return $this->connection->query($query);
    }

    private function hasWhere(): string
    {
        return $this->where ? "where %s" : '';
    }

    private function hasOrderBy(): string
    {
        return $this->orderBy ? 'order by %s' : '';
    }

    public function select(string $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function getWhere(): string
    {
        return str_replace(array_keys($this->parameters), array_map(function ($param) {
            $this->connection->escape_string($param);
        }, $this->parameters), $this->where);
    }

    public function setWhere(string $where): self
    {
        $this->where = $where;
        return $this;
    }

    public function setParameters(array $params): self
    {
        $this->parameters = $params;
        return $this;
    }

    public function getOrderBy(): string
    {
        return $this->hasOrderBy() ? sprintf("%s %s", $this->orderBy, $this->direction) : '';
    }

    public function setOrderBy($orderItem): self
    {
        $this->orderBy = $orderItem;
        return $this;
    }

    public function setDirection($direction): self
    {
        $this->direction = $direction;
        return $this;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @param string|null $limit
     * @return self|int
     */
    public function limit(string $limit = null)
    {
        if ($limit) {
            $this->limit = $limit;
            return $this;
        }
        return $this->limit ?: 10;
    }

    /**
     * @param int|null $page
     * @return self|float|int
     */
    public function page(int $page = null)
    {
        if ($page) {
            $this->page = $page;
            return $this;
        }
        return $this->limit() * (($this->page ?: 1) - 1);
    }

    private function filterOutEmpty($var): bool
    {
        return ($var !== NULL && $var !== FALSE && $var !== '');
    }

    public function getOrder(): string
    {
        return $this->orderBy;
    }
}