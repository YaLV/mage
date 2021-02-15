<style>
    table td, table th {
        border: 1px solid black;
        text-align: center;
    }

    [data-sort] {
        cursor: pointer;
    }

    div.page {
        width: 15px;
        height: 15px;
        display: inline-block;
    }

    div.paginator {
        margin: auto;
        white-space: nowrap;
        text-align: center;
        margin-top: 10px;
    }
</style>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function () {
        $('[data-page]').click(function () {
            $('#page').val($(this).data('page'));
            $('form').submit();
            return false;
        });

        $('[data-delete]').click(function () {
            if(confirm("Delete this subscription?")) {
                $('#delete').val($(this).data('delete')).prop('disabled', false);
                $('form').submit();
            }
            return false;
        })

        $('[data-sort]').click(function () {
            let oldOrderBy = $('#orderby').val(),
                oldDirection = $('#direction').val(),
                currentOrderBy = $(this).data('sort');

            let reverse = oldOrderBy == currentOrderBy;
            $('#orderby').val(currentOrderBy);
            $('#direction').val(!reverse ? 'asc' : (oldDirection == 'asc' ? 'desc' : 'asc'));
            $('form').submit();
            return false;
        });

        $('#export').click(function () {
           if($('.export:checked').length>0) {
               let ids = [];
               $('.export:checked').each(function () { ids.push($(this).val()) });
               window.open('?section=admin&export='+ids.join(','));
           }
            return false;
        });
    });
</script>
<form>
    <input type="hidden" name="section" value="admin"/>
    <input type="hidden" name="page" value="<?= $page ?? 1 ?>" id="page"/>
    <input type="hidden" id="delete" name="delete" value="" disabled/>
    <input type="hidden" id="orderby" name="orderby" value="<?= $request->get('orderby') ?? 'created_at'; ?>"/>
    <input type="hidden" id="direction" name="direction" value="<?= $request->get('direction') ?? 'asc'; ?>"/>
    <div style="width: 50%; margin: auto;">
        <div style="float: left; width:70%;">
            <table style="border-collapse: collapse;width:100%;">
                <thead>
                <tr>
                    <th><a href="#" id="export">Export</a></th>
                    <th>
                        <div data-sort="id">
                            ID
                            <span style='float:right'><?= ($subscriptions->getOrder() == 'id' ? $subscriptions->getDirection() : '') ?></span>
                        </div>
                        <input type="text" name="filter[id]" size="5" value="<?= $filters['id'] ?? '' ?>"/>
                    </th>
                    <th>
                        <div data-sort="email">
                            Email
                            <span style='float:right'><?= ($subscriptions->getOrder() == 'email' ? $subscriptions->getDirection() : '') ?></span>
                        </div>
                        <input type="text" name="filter[email]" value="<?= $filters['email'] ?? '' ?>"/>
                    </th>
                    <th>
                        <div data-sort="created_at">
                            Created at:
                            <span style='float:right'><?= ($subscriptions->getOrder() == 'created_at' ? $subscriptions->getDirection() : '') ?></span>
                        </div>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($items as $item) {
                    echo "
                    <tr>
                        <td><input type='checkbox' class='export' name='export[]' value='{$item['id']}' /></td>
                        <td>{$item['id']}</td>
                        <td>{$item['email']}</td>
                        <td>{$item['created_at']}</td>
                        <td>
                            <a href='#' data-delete='{$item['id']}'>Delete</a>
                        </td>
                    </tr>
                    ";
                }
                ?>
                </tbody>
            </table>
            <div class="paginator">
                <?php
                if ($totalAmount > 1) {
                    ?>
                    <div class="page">
                        <a href="#"
                           data-page="<?= ($page ?? 1) - 1 ?>"
                            <?= (($page ?? 1) <= 1 ? 'style="pointer-events:none;"' : '') ?>>&laquo;</a>
                    </div>
                    <?php
                    foreach (range(1, $totalAmount) as $pageIterator) {
                        ?>
                        <div class="page">
                            <a href="#"
                               data-page="<?= $pageIterator ?>"
                                <?= (($page ?? 1) == $pageIterator ? 'style="pointer-events:none;"' : '') ?>><?= $pageIterator ?></a>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="page">
                        <a href="#"
                           data-page="<?= ($page ?? 1) + 1 ?>"
                            <?= (($page ?? 1) >= $totalAmount ? 'style="pointer-events:none;"' : '') ?>>&raquo;</a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div style="float:right;vertical-align: top;">
            <ul>
                <?php
                foreach ($domains as $domain) {
                    echo "<li><input type='checkbox' name='filter[domain][]' " . (in_array($domain['domain'], $filters['domain'] ?? []) ? 'checked' : '') . " value='{$domain['domain']}' id='{$domain['domain']}' /><label for='{$domain['domain']}'>{$domain['domain']}</label></li>";
                }
                ?>
            </ul>

            <input type="submit" value="Filter"/>
        </div>
    </div>
</form>