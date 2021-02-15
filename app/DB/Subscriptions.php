<?php


namespace App\DB;


class Subscriptions extends MySQL
{
    /**
     * @var string
     */
    public $table = "subscriptions";

    /**
     * @var string[]
     */
    public $schema = [
        'id' => 'int(11) not null auto_increment',
        'email' => 'varchar(255) not null',
        'domain' => 'varchar(255) not null',
        'created_at' => 'datetime not null'
    ];

    /**
     * @var array
     */
    public $newData;

    public function __construct(array $data = null) {
        parent::__construct();

        if($data !== null) {
            $now = new \DateTime();
            $emailParts = explode("@", $data['email']);
            $domain = array_pop($emailParts);
            $data['domain'] = $domain;
            $data['created_at'] = $now->format('Y-m-d H:i:s');
            $data = array_intersect_key($data, $this->schema);
            foreach($data as &$item) {
                $item = $this->getConnection()->escape_string($item);
            }
            $this->newData = $data;
        }
    }

    public function createTable(): void {
        $sql = "
        CREATE TABLE IF NOT EXISTS `subscriptions` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `email` varchar(255) NOT NULL,
          `domain` varchar(255) NOT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->getConnection()->query($sql);
    }
}