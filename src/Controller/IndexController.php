<?php

namespace Phire\Stats\Controller;

use Phire\Stats\Form;
use Phire\Stats\Model;
use Pop\Controller\AbstractController;
use Pop\Http\Request;
use Pop\Http\Response;
use Pop\Paginator\Paginator;
use Pop\View\View;
use Pop\Web\Session;

class IndexController extends AbstractController
{

    /**
     * Session object
     * @var Session
     */
    protected $sess = null;

    /**
     * Request object
     * @var Request
     */
    protected $request = null;

    /**
     * Response object
     * @var Response
     */
    protected $response = null;

    /**
     * View path
     * @var string
     */
    protected $viewPath = null;

    /**
     * View object
     * @var View
     */
    protected $view = null;

    /**
     * Pagination limit
     * @var int
     */
    protected $pagination = 25;

    /**
     * Constructor for the controller
     *
     * @return IndexController
     */
    public function __construct()
    {
        $this->request  = new Request();
        $this->response = new Response();
        $this->sess     = Session::getInstance();
        $this->viewPath = __DIR__ . '/../../view/';
    }

    public function index()
    {
        if (!isset($this->sess->user)) {
            $this->redirect('/login');
        }

        $this->prepareView('index.phtml');

        if (null !== $this->request->getQuery('modules')) {
            $model = new Model\Module();
            $this->view->title = 'Module Stats';
        } else if (null !== $this->request->getQuery('themes')) {
            $model = new Model\Theme();
            $this->view->title = 'Theme Stats';
        } else {
            $model = new Model\System();
            $this->view->title = 'System Stats';
        }

        if ($model->hasPages($this->pagination)) {
            $limit = $this->pagination;
            $pages = new Paginator($model->getCount(), $limit);
            $pages->useInput(true);
        } else {
            $limit = null;
            $pages = null;
        }

        $this->view->pages = $pages;
        $this->view->stats = $model->getAll(
            $limit, $this->request->getQuery('page'), $this->request->getQuery('sort')
        );

        $this->send();
    }

    public function login()
    {
        if (isset($this->sess->user)) {
            $this->redirect('/');
        }

        $this->prepareView('login.phtml');
        $this->view->title = 'Login';
        $this->view->form  = new Form\Login();

        if ($this->request->isPost()) {
            $this->view->form->addFilter('strip_tags')
                 ->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
                 ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->sess->user = true;
                $this->redirect('/');
            }
        }

        $this->send();
    }

    public function system()
    {
        echo 'System!';
    }

    public function module()
    {
        echo 'Module!';
    }

    public function theme()
    {
        echo 'Theme!';
    }

    public function error()
    {
        $this->prepareView('error.phtml');
        $this->view->title = 'Error';
        $this->send(404);
    }

    public function logout()
    {
        $this->sess->kill();
        $this->redirect('/login');
    }

    public function send($code = 200, array $headers = null, $body = null)
    {
        $this->response->setCode($code);

        if (null !== $body) {
            $this->response->setBody($body);
        } else if (null !== $this->view) {
            $this->response->setBody($this->view->render());
        }

        $this->response->send($code, $headers);
    }

    public function redirect($url, $code = '302', $version = '1.1')
    {
        Response::redirect($url, $code, $version);
        exit();
    }

    protected function prepareView($template)
    {
        $this->view = new View($this->viewPath . $template);
    }

}