<?php

/**
 * Sample routes controller.
 * 
 * @package api-framework
 * @author  Martin Bean <martin@martinbean.co.uk>
 */
class RoutesController extends AbstractController
{
    /**
     * Routes file.
     *
     * @var variable type
     */
    protected $articles_file = './data/routes.txt';
    
    /**
     * GET method.
     * 
     * @param  Request $request
     * @return string
     */
    public function get($request)
    {
        $articles = $this->readArticles();
        switch (count($request->url_elements)) {
            case 1:
                return $articles;
            break;
            case 2:
                $article_id = $request->url_elements[1];
                return $articles[$article_id];
            break;
        }
    }
    
    /**
     * POST action.
     *
     * @param  $request
     * @return null
     */
    public function post($request)
    {
        switch (count($request->url_elements)) {
            case 1:
                // validation should go here
                $id = (count($articles) + 1);
                $articles = $this->readArticles();
                $article = array(
                    'id' => $id,    
                    'title' => $request->parameters['title'],
                    'content' => $request->parameters['content'],
                    'published' => date('c')
                );
                $articles[] = $article;
                $this->writeArticles($articles);
                header('HTTP/1.1 201 Created');
                header('Location: /routes/'.$id);
                return null;
            break;
        }
    }
    
    /**
     * Read articles.
     *
     * @return array
     */
    protected function readArticles()
    {
        $host="codethesolution.com"; //replace with database hostname 
        $username="drivemethere"; //replace with database username 
        $password="hackathon123!"; //replace with database password 
        $db_name="drivemethere"; //replace with database name
        
        $con=mysql_connect("$host", "$username", "$password")or die("cannot connect");
        mysql_select_db("$db_name")or die("cannot select DB");
        $sql = "SELECT * FROM Routes";
        $result = mysql_query($sql);
        $json = array();
            //  $result;
          
        if(mysql_num_rows($result)){
            while($row=mysql_fetch_assoc($result)){ //for each row returned, create position in array
            $id = array_shift($row);
            $json[$id][]=$row;
            }
        }
        mysql_close($con);
      //  $articles = $json;

        
       /*  $articles = unserialize(file_get_contents($this->articles_file));
        if (empty($articles)) {
            $articles = array(
                1 => array(
                    'id' => 1,
                    'title' => 'Test Article',
                    'content' => 'Welcome to your new API framework!',
                    'published' => date('c', mktime(18, 35, 48, 1, 13, 2012))
                )
            );
            $this->writeArticles($articles);
        } */
        return $json;
    }
    
    /**
     * Write articles.
     *
     * @param  string $articles
     * @return boolean
     */
    protected function writeArticles($articles)
    {
        file_put_contents($this->articles_file, serialize($articles));
        return true;
    }
}