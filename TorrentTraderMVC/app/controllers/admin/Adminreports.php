<?php
class Adminreports extends Controller
{

    public function __construct()
    {
        $this->user = (new Auth)->user(_MODERATOR, 2);
        // $this->userModel = $this->model('User');
        $this->logsModel = $this->model('Logs');
        $this->valid = new Validation();
    }


    public function index()
    {
        $page = 'adminreports?';
        $pager[] = substr($page, 0, -4);
        $where = array();
        switch ($_GET["type"]) {
            case "user":
                $where[] = "type = 'user'";
                $pager[] = "type=user";
                break;
            case "torrent":
                $where[] = "type = 'torrent'";
                $pager[] = "type=torrent";
                break;
            case "comment":
                $where[] = "type = 'comment'";
                $pager[] = "type=comment";
                break;
            case "forum":
                $where[] = "type = 'forum'";
                $pager[] = "type=forum";
                break;
            case "req":
                $where[] = "type = 'req'";
                $pager[] = "type=req";
                break;
            default:
                $where = null;
                break;
        }
        switch ($_GET["completed"]) {
            case 1:
                $where[] = "complete = '1'";
                $pager[] = "complete=1";
                break;
            default:
                $where[] = "complete = '0'";
                $pager[] = "complete=0";
                break;
        }
        $where = implode(" AND ", $where);
        $pager = implode("&amp;", $pager);
        $num = get_row_count("reports", "WHERE $where");
        list($pagertop, $pagerbottom, $limit) = pager(25, $num, "$pager&amp;");
        $res = DB::run("SELECT reports.id, reports.dealtwith, reports.dealtby, reports.addedby, reports.votedfor, reports.votedfor_xtra, reports.reason, reports.type, users.username, reports.complete FROM `reports` INNER JOIN users ON reports.addedby = users.id WHERE $where ORDER BY reports.id DESC $limit");
        $data = [
            'title' => Lang::T("Reported Items"),
            'res' => $res,
            'page' => $page,
        ];
        $this->view('report/admin/index', $data, 'admin');
    }


    public function completed()
    {
        if ($_POST["mark"]) {
            if (!@count($_POST["reports"])) {
                show_error_msg(Lang::T("ERROR"), "Nothing selected to mark.", 1);
            }
            $ids = array_map("intval", $_POST["reports"]);
            $ids = implode(",", $ids);
            DB::run("UPDATE reports SET complete = '1', dealtwith = '1', dealtby = '$_SESSION[id]' WHERE id IN ($ids)");
            header("Refresh: 2; url=" . URLROOT . "/adminreports");
            show_error_msg(Lang::T("SUCCESS"), Lang::T("CP_ENTRIES_MARK_COMP"), 1);
        }
        if ($_POST["del"]) {
            if (!@count($_POST["reports"])) {
                show_error_msg(Lang::T("ERROR"), "Nothing selected to delete.", 1);
            }
            $ids = array_map("intval", $_POST["reports"]);
            $ids = implode(",", $ids);
            DB::run("DELETE FROM reports WHERE id IN ($ids)");
            header("Refresh: 2; url=" . URLROOT . "/adminreports");
            show_error_msg(Lang::T("SUCCESS"), "Entries marked deleted.", 1);
        }
    }

}