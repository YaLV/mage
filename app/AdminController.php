<?php


namespace App;


class AdminController
{
    const TEMPLATE = "admin.php";

    const RECORDS_PER_PAGE = 10;

    /**
     * @param Request $request
     * @return mixed|void
     */
    public static function index(Request $request)
    {
        $subscriptions = new \App\DB\Subscriptions();

        if (!$request->get('export')) {
            if ($request->get('delete')) {
                $subscriptions->delete($request->get('delete'));
                $params = $request->get();
                unset($params['delete']);
                header("location: ?" . http_build_query($params));
                return;
            }

            $subscriptions->select('distinct(domain) as domain');
            $subscriptions->limit(9999);
            $page = $request->get('page');

            $domains = $subscriptions->get();
            $filters = $request->get('filter');
            if ($filters) {
                $where = [];
                $availableFilters = array_keys($subscriptions->schema);
                foreach ($filters as $filterName => $filterValue) {
                    if (!in_array($filterName, $availableFilters)) {
                        continue;
                    }

                    switch ($filterName) {
                        default:
                            $where[] = "$filterName like '%$filterValue%'";
                            break;

                        case 'domain':
                            $domainWhere = [];
                            foreach ($filterValue as $domain) {
                                $domainWhere[] = "domain='$domain'";
                            }
                            $where[] = "(" . implode(" or ", $domainWhere) . ")";
                            break;

                    }
                }
                if (count($where) > 0) {
                    $subscriptions->setWhere(implode(" and ", $where));
                }
            }

            $subscriptions->select('count(*) as totalAmount');
            $subscriptions->page(1);
            $totalAmount = $subscriptions->get();
            $totalAmount = ceil($totalAmount[0]['totalAmount'] / self::RECORDS_PER_PAGE);
        } else {
            $subscriptions->setWhere("id in ({$request->get('export')})");
        }
        $subscriptions->select('*');
        $subscriptions->page($request->get('page') ?? 1)
            ->limit(self::RECORDS_PER_PAGE)
            ->setOrderBy($request->get('orderby') ?? 'created_at')
            ->setDirection($request->get('direction') ?? 'asc');

        $items = $subscriptions->get();

        if ($request->get('export')) {
            $lines = [];
            foreach ($items as $item) {
                if(count($lines)==0) {
                    $lines[] = '"' . implode('";"', array_keys($item)) . '"';
                }
                $lines[] = '"' . implode('";"', $item) . '"';
            }
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename=export.csv');
            echo implode("\n", $lines);
            return;
        }
        return include(implode(DIRECTORY_SEPARATOR, ['app', 'templates', self::TEMPLATE]));
    }
}