<?php
class Rules extends Controller
{

    public function __construct()
    {
        $this->user = $this->user = (new Auth)->user(0, 2);
        $this->rulesModel = $this->model('Rule');
    }

    public function index()
    {
        $res = $this->rulesModel->getRules();
        $data = [
            'title' => 'Rules',
            'res' => $res
        ];
        $this->view('rules/index', $data, 'user');
    }
}
