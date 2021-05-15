<?php
Style::begin(Lang::T("TORRENTS_FREE_LEECH"));
    print("<center><form method='get' action='?'>\n");
    print("<input type='hidden' name='action' value='freetorrents' />\n");
    print(Lang::T("SEARCH") . ": <input type='text' size='30' name='search' />\n");
    print("<input type='submit' value='Search' />\n");
    print("</form></center>\n");
    echo $data['pagertop'];
    ?>
	<table class='table table-striped table-bordered table-hover'><thead>
	<tr>
        <th class="table_head"><?php echo Lang::T("NAME"); ?></th>
        <th class="table_head"><?php echo Lang::T("VISIBLE"); ?></th>
        <th class="table_head"><?php echo Lang::T("BANNED"); ?></th>
        <th class="table_head"><?php echo Lang::T("SEEDERS"); ?></th>
        <th class="table_head"><?php echo Lang::T("LEECHERS"); ?></th>
        <th class="table_head"><?php echo Lang::T("EDIT"); ?></th>
	</tr></thead><tbody>
	<?php
    while ($row = $data['resqq']->fetch(PDO::FETCH_LAZY)) {
        $char1 = 35; //cut name length
        $smallname = CutName(htmlspecialchars($row["name"]), $char1);
        echo "<tr><td class='table_col1'>" . $smallname . "</td><td class='table_col2'>$row[visible]</td><td class='table_col1'>$row[banned]</td><td class='table_col2'>" . number_format($row["seeders"]) . "</td><td class='table_col1'>" . number_format($row["leechers"]) . "</td><td class='table_col2'><a href=\"torrents/edit?returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;id=" . $row["id"] . "\"><font size='1' face='verdana'>EDIT</font></a></td></tr>\n";
    }
    echo "</tbody></table>\n";
    print($data['pagerbottom']);
    Style::end();