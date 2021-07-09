<?php
class Warning extends Controller
{
    public function __construct()
    {
        $this->session = Auth::user(0, 2);
    }

    public function index()
    {
        // Get User
        $id = (int) Input::get("id");
        $user = Users::getUserById($id);
        if (!$user) {
            Redirect::autolink(URLROOT . '/group/members', Lang::T("NO_USER_WITH_ID") . " $id.");
        }
        // Checks
        if ($this->session["view_users"] == "no" && $this->session["id"] != $id) {
            Redirect::autolink(URLROOT . '/home', Lang::T("NO_USER_VIEW"));
        }
        if (($this->session["enabled"] == "no" || ($this->session["status"] == "pending")) && $this->session["edit_users"] == "no") {
            Redirect::autolink(URLROOT . '/group/members', Lang::T("NO_ACCESS_ACCOUNT_DISABLED"));
        }
        // Get Warnings
        $warning = Warnings::getWarningById($user['id']);
        // Title
        $title = sprintf(Lang::T("USER_DETAILS_FOR"), Users::coloredname($user["username"]));
        // Template
        $data = [
            'title' => $title,
            'res' => $warning,
            'id' => $user['id'],
            'username' => $user['username'],
        ];
        View::render('warning/index', $data, 'user');
    }

    public function submit()
    {
        // Get Inputs
        $userid = (int) Input::get("userid");
        $reason = Input::get("reason");
        $expiry = (int) Input::get("expiry");
        $type = Input::get("type");
        // Checks
        if ($this->session["edit_users"] != "yes") {
            Redirect::autolink(URLROOT . "/profile?id=$userid", Lang::T("TASK_ADMIN"));
        }
        if (!Validate::Id($userid)) {
            Redirect::autolink(URLROOT . '/group/members', Lang::T("INVALID_USERID"));
        }
        if (!$reason || !$expiry || !$type) {
            Redirect::autolink(URLROOT . "/profile?id=$userid", Lang::T("MISSING_FORM_DATA"));
        }
        // Times
        $timenow = TimeDate::get_date_time();
        $expiretime = TimeDate::get_date_time(TimeDate::gmtime() + (86400 * $expiry));
        // Insert Warning
        Warnings::insertWarning($userid, $reason, $timenow, $expiretime, $this->session['id'], $type);
        Users::warnUserWithId($userid);
        // Message & Log
        $msg = "You have been warned by " . $this->session["username"] . " - Reason: " . $reason . " - Expiry: " . $expiretime . "";
        $added = TimeDate::get_date_time();
        Message::insertmessage(0, $userid, $added, 'New Warning', $msg, 'yes', 'in');
        Logs::write($this->session['username'] . " has added a warning for user: <a href='" . URLROOT . "/profile?id=$userid'>$userid</a>");
        Redirect::autolink(URLROOT . "/profile?id=$userid", "Warning given");
    }

}