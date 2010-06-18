<?php
    define('CURL_TIMEOUT_SET', 5);
    define('HOSTNAME', 'josephjiang.com');
    define('HTTPHOST', 'http://josephjiang.com/');
    define('STATICHOST', 'http://img.josephjiang.com/');
    function getLoginCookie() {
        if (!isset($_COOKIE['user']) || empty($_COOKIE['user'])) {
            return false;
        };
        return $_COOKIE['user'];
    };
    function setLoginCookie($user) {
        setcookie('user', $user, time()+86400*7);
    };
    $global_user = getLoginCookie();
    function getBlogArticleRecentList() {
    	$result = '';
    	$dbh = mysql_connect('localhost', 'root', '');
        mysql_select_db('josephjiang', $dbh);
        $res = mysql_query('SELECT * FROM blog_article WHERE is_show = "y" ORDER BY last_update DESC LIMIT 10', $dbh);
        while ($row = mysql_fetch_assoc($res)) {
            $result[] = $row;
        }
        return $result;
    }
    function getBlogArticleColumns() {
        return array(
            'background', 
            'blog_article_id', 
            'blog_category_id', 
            'blog_category_title', 
            'blog_comment_total',
            'content', 
            'is_show', 
            'is_nl2br', 
            'keyword', 
            'last_update',
            'selected_date',
            'summary',
            'title' 
        );
    };
    function getBlogCategoryColumns() {
        return array(
            'blog_category_id',
            'added_date',
            'blog_article_total',
            'content',
            'title'
        );
    };
    function getBlogCommentColumns() {
        return array(
            'blog_comment_id',
            'blog_article_id',
            'blog_article_title',
            'added_date',
            'content',
            'email',
            'is_show',
            'nickname'
        );
    };
    function isLogin() {                                                                                                                                                        
        global $global_user;                                                                                                                              
        if (!$global_user || empty($global_user)) {                                                                                                              
            return false;                                                                                                                                 
        };                                                                                                                                                     
        $g = $global_user;                                                                                                                                                                                                                                                                                      
        if (isset($global_user) && !empty($global_user)) {                                                                                                               
            return true;                                                                                                                              
        };                                                                                                                                                                                                                                                                                 
        return false;                                                                                                                                     
    };      
    function checkAuth($user, $password) {
        $users = SelectData('user', array('user', 'nickname'), array('user' => $user, 'password' => md5($user . $password . RAND_PASSWORDKEY)));
        if (count($users)) {
            return true;
        }
        else {
            return false;
        };
    };
    function checkToken($hash, $article_id) {
        db_connect();
        $sql = 'SELECT COUNT(*) As total FROM blog_token';
        $sql .= ' WHERE hash = "' . $hash . '"';
        $sql .= ' AND ((current_access < max_access) OR (max_access IS NULL))';
        $result = mysql_query($sql);
        $total = mysql_fetch_assoc($result);
        $total = $total['total'];
        if (!$total) { 
            return false;
        };
        return true;
    };
    function updateToken($hash) {
        db_connect();
        $date = date('Y-m-d H:i:s');
        $sql = 'UPDATE blog_token SET';
        $sql .= ' latest_ip = "' . getUserIp() . '"'; 
        $sql .= ', latest_time = "' . $date . '"';
        $sql .= ', current_access = current_access+1';
        $sql .= ' WHERE hash = "' . $hash . '"'; 
        return mysql_query($sql);
    };
    function getBlogCategoryList() {
        return SelectData('blog_category', getBlogCategoryColumns(),'',array('blog_article_total DESC'));
    };
    function getBlogCategoryInfo($id) {
        $id = intval($id);
        if (!is_int($id)) {
            return false;
        };
        return SelectData('blog_category', getBlogCategoryColumns(), array('blog_category_id'=>$id));
    };
    function setBlogCategoryTotal($id) {
        $id = intval($id);
        if (!is_int($id)) {
            return false;
        };
        $articles = SelectData('blog_article', array('count(*)'), array('blog_category_id' => $id, 'is_show' => 'y'));
        $total = $articles[0]['count(*)']; 
        UpdateData('blog_category', array('blog_article_total' => $total), array('blog_category_id' => $id)); 
        return true;
    };
    function addBlogArticleToken($article_id, $token_content, $max_access, $hash) {
        InsertData('blog_token', array('content' => $token_content, 'max_access' => $max_access, 'hash' => $hash, 'blog_article_id' => $article_id));
        return true;
    };
    function addBlogArticle($column_array = '') {
        InsertData('blog_article', $column_array);
        return true;
    };
    function deleteBlogArticle($id) {
        $id = intval($id);
        if (!is_int($id)) {
            return false;
        };
        DeleteData('blog_article', array('blog_article_id' => $id));
        return true;
    };
    function getBlogArticleTotal($is_show_only = true, $category_id = 0) {
        $filter = ($is_show_only) ? array('is_show' => 'y') : '';
        if ($category_id) {
            $filter = ($filter !== '') ? array_merge($filter, array('blog_category_id' => $category_id)) : array('category_id' => $category_id);
        }
        $result = SelectData('blog_article', array('count(*)'), $filter);
        $total = $result[0]['count(*)']; 
        return $total;
    };
    // 取得鄰近的兩篇文章
    function getBlogArticleNearby($id) {
        $id = intval($id);
        if (!is_int($id)) {
            return false;
        };
        db_connect();
        $sql = 'SELECT * FROM `blog_article` WHERE is_show = "y" AND blog_article_id < ' . $id . ' ORDER BY `blog_article_id` DESC LIMIT 0,1';
        $resource = mysql_query($sql);
        while ($row = mysql_fetch_assoc($resource)) {
            $result['previous'] = $row;
        };
        $sql = 'SELECT * FROM `blog_article` WHERE is_show = "y" AND blog_article_id > ' . $id . ' ORDER BY `blog_article_id` ASC LIMIT 0,1';
        $resource = mysql_query($sql);
        while ($row = mysql_fetch_assoc($resource)) {
            $result['next'] = $row;
        };
        return $result;
    };
    function getBlogArticleList($is_show_only = true, $begin, $page_size, $category_id = 0) {
        db_connect();
        if ($is_show_only) { 
            $category_filter = ($category_id != 0) ? ' AND blog_category_id = '.$category_id : '';
            $sql = sprintf('SELECT * FROM `blog_article` WHERE is_show = "y"'.$category_filter.' ORDER BY `selected_date` DESC LIMIT %d, %d', $begin, $page_size); 
        }
        else {
            $category_filter = ($category_id != 0) ? ' WHERE blog_category_id = '.$category_id : '';
            $sql = sprintf('SELECT * FROM `blog_article`'.$category_filter.' ORDER BY `selected_date` DESC LIMIT %d, %d', $begin, $page_size); 
        }
        $resource = mysql_query($sql);
        while ($row = mysql_fetch_assoc($resource)) {
            $result[] = $row;
        };
        return $result;
    };
    function getBlogArticleInfo($id) {
        $id = intval($id);
        if (!is_int($id)) {
            return false;
        };
        $result = SelectData('blog_article', getBlogArticleColumns(), array('blog_article_id' => $id), '');
        return $result;
    };
    function setBlogArticle($id, $column_array) {
        $id = intval($id);
        if (!is_int($id)) {
            return false;
        };
        UpdateData('blog_article', $column_array, array('blog_article_id' => $id)); 
        return true;
    };
    function getBlogCommentList($id, $is_show_only = 'y', $limit) {
        $id = intval($id);
        if (!is_int($id)) {
            $id = false;
        };
        $limit = intval($limit);
        if (!is_int($limit)) {
            $limit = false;
        };
        if ($id) {
            $result = SelectData('blog_comment', getBlogCommentColumns(), ($is_show_only) ? array('blog_article_id' => $id, 'is_show' => 'y') : '', array('blog_comment_id DESC'.(($limit)?' LIMIT '.$limit:''))); 
        }
        else {
            $result = SelectData('blog_comment', getBlogCommentColumns(), ($is_show_only) ? array('is_show' => 'y') : '', array('blog_comment_id DESC'.(($limit)?' LIMIT '.$limit:''))); 
        };
        return $result;
    };
    function addBlogComment($column_array = '') {
        InsertData('blog_comment', $column_array);
        return true;
    };
    function setBlogArticleCommentTotal($id) {
        $id = intval($id);
        if (!is_int($id)) {
            return false;
        };
        $total = SelectData('blog_comment', array('count(*)'), array('blog_article_id' => $id, 'is_show' => 'y'));
        $total = $total[0]['count(*)']; 
        UpdateData('blog_article', array('blog_comment_total' => $total), array('blog_article_id' => $id)); 
        return true;
    };
    function getCache($url, $ttl = 43200) {
        if(!$result = apc_fetch($url)) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, "$url");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-TW; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11");
            $result = curl_exec($ch);
            if ($result === false) {
                return false;
            };
            apc_store($url,$result,$ttl);
        };
        return $result;
    };
    function getLink($num = 5) {
        $request = 'http://feeds.delicious.com/feeds/json/josephj/?count='.$num.'&raw';
        $response = getCache($request);
        if (!$response) {
            return false;
        };
        $result = json_decode(stripslashes($response), true);
        return $result;
    };
    function getPhoto() {
        $request = 'http://api.flickr.com/services/feeds/photos_public.gne?id=87715586@N00&format=json&nojsoncallback=1';
        $response = getCache($request);
        if (!$response) {
            return false;
        };
        return json_decode($response,true);
    };
    function getStatus() {
        $url = 'http://twitter.com/statuses/user_timeline/12344892.json';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-TW; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11");
        $response = curl_exec($ch);
        return json_decode($response,true);
    };
    function getVisitor() {
        $community_id = '2008010222451982';
        $appid = 'a1246r_V34Foktsyk7.L38MUruS._8yvevWxQ6oOpZDP05w69SWs8g2gw7Z8jqinyAU-';
        $format = 'json';
        $count = '30';
        $request = "http://mybloglog.yahooapis.com/v1/community/$community_id/readers/?appid=$appid&format=$format&count=30";
        $response = getCache($request);
        if (!$response) {
            return false;
        };
        $visitors = json_decode($response, true); 
        $visitors = $visitors['users'];
        $visitors = $visitors['user'];
        return $visitors;
    };
    function getTagList() {
        $request = 'http://api.sitetag.us/api.php?hash=453a06614b37465163ceb6428aae9f4e&method=sitetag.getTagListBySite&output=json&limit=30&extra=site&v=2';
        $response = getCache($request);
        if (!$response) {
            return false;
        };
        $data = json_decode($response,true);
        return $data['rsp']['keywords'];
    };
    function getGET($var, $value='') {
        return isset($_GET[$var]) ? trim($_GET[$var]) : $value ;
    };
    function getPOST($var, $value='') {
        return isset($_POST[$var]) ? trim($_POST[$var]) : $value ;
    };
    function getREQUEST($var, $value='') { 
        return isset($_REQUEST[$var]) ? trim($_REQUEST[$var]) : $value ;
    };
    function getUserIp() {
        $HTTP_ENV_VARS = $_ENV;
        $HTTP_SERVER_VARS = $_SERVER;
        if (getenv('HTTP_X_FORWARDED_FOR') != '') {
            $proxy_ip = (!empty($HTTP_SERVER_VARS['REMOTE_ADDR'])) ?
                $HTTP_SERVER_VARS['REMOTE_ADDR'] :
                ((!empty($HTTP_ENV_VARS['REMOTE_ADDR'])) ?
                 $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR);
            $client_ip = getenv('HTTP_X_FORWARDED_FOR');
        } 
        else {
            $client_ip = (!empty($HTTP_SERVER_VARS['REMOTE_ADDR']))?
                $HTTP_SERVER_VARS['REMOTE_ADDR'] :
                ((!empty($HTTP_ENV_VARS['REMOTE_ADDR'])) ?
                 $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR);
            $proxy_ip = '';
        }
        return $client_ip;
    };
    function getSvnId($rev) {
        $rev = str_replace('$', '', $rev);
        $rev = str_replace('Rev:', '', $rev);
        return intval($rev);
    };
    function getCssVersion() {
        if (file_exists('../sitetag.us/CSS_VERSION')) {
            return intval(file_get_contents('../sitetag.us/CSS_VERSION'));
        } 
        else {
            return 1;
        }
    };
    function getJsVersion() {
        if (file_exists('../sitetag.us/JS_VERSION')) {
            return intval(file_get_contents('../sitetag.us/JS_VERSION'));
        } 
        else {
            return 1;
        };
    };
    // {{{ ErrorLog($error_msg, $type=3, $path=ERROR_PATH, $error_fname=ERROR_FILENAME)
    /**
     * @brief record error log
     * @param error_msg - error message record
     * @param type - error_log message code
     *             -# 0 message is sent to PHP's system logger
     *             -# 1 message is sent by email to the address in the destination parameter
     *             -# 2 message is sent through the PHP debugging connection
     *             -# 3 message is appended to the file destination
     * @param path - message appended to the file destination path.
     * @param error_fname - save error log filename
     */
    function ErrorLog($error_msg, $type=3, $path=ERROR_PATH, $error_fname=ERROR_FILENAME)
    {
        $path .= ($error_fname == -1)?'error_'.date('Ymd').'.log':$error_fname;

        $msg = date('Y/m/d H:i:s');

        $filename = getenv('REQUEST_URI');
        if( $filename == '' ) {
            global $argv;
            $filename = $argv[0] ;
        }
        $msg .= ' [' . $filename . '] ';

        // debug backtrace on/off (1/0)
        if( defined('ERROR_TRACE_ENABLE') && ERROR_TRACE_ENABLE ) {
            $msg = $msg ."\n";
            $history = debug_backtrace();
            foreach ( $history as $i => $line )
                $msg .= "    #$i  {$line['function']} called at [{$line['file']}:{$line['line']}]\n" ;
        }

        $msg .= "    [MSG] $error_msg \n\n";

        error_log($msg, $type, $path);

        return true;
    }
    // }}}
    // {{{ getMicrotimes()
    /**
     * 抓取現在時間微秒, 計算程式執行時間.
     * @return Microtimes
     */
    function getMicrotimes()
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((double)$usec + (double)$sec);
    }
    // }}}
    // {{{ LeaveNow($date, $now=0)
    /**
     * 傳入時間, 自動算出跟現在時間差幾天/幾小時(24小時內以小時顯示) ...
     * n秒前/n分鐘前/n小時前/n天前/n月前/n年前
     * @param $date: date('Y-m-d H:i:s')
     * @param $now : date('Y-m-d H:i:s')
     * @return 幾天/幾小時(24小時內以小時顯示)
     */
    function LeaveNow($date, $now=0)
    {
        $now   = ( $now==0 ) ? time() : strtotime($now);
        $leave = $now - strtotime($date);

        if( 60 > $leave ) {
            return $leave . _("second(s) ago");
        } else if(3600 > $leave) { // 60 > $leave % 60, 1 min = 60 sec, 60 * 60
            return intval($leave / 60) . _("minute(s) ago");
        } else if(24 > $leave / 3600) { // 1 day = 24 hr, 3600 * 60
            return intval($leave / 3600) . _("hour(s) ago");
        } else if(30 > $leave / 86400) { // 1 month = 30 day, 3600 * 24
            return intval($leave / 86400) . _("day(s) ago");
        } else if(12 > $leave / 2592000) { // 1 year = 12 month, 86400 * 30
            return intval($leave / 2592000) . _("month(s) ago");
        } else { // 1 year = 12 month, 259200 * 12
            return intval($leave / 31104000) . _("year(s) ago");
        }
    }
    //$now = '2006-11-26 23:12:05';
    //$date = '2004-01-01 1:1:1';
    //echo LeaveNow($date, $now)."\n"; // 2年前
    //$date = '2006-10-01 1:1:1';
    //echo LeaveNow($date, $now)."\n"; // 1月前
    //$date = '2006-11-01 1:1:1';
    //echo LeaveNow($date, $now)."\n"; // 25天前
    //$date = '2006-11-26 23:0:0';
    //echo LeaveNow($date, $now)."\n"; // 12分鐘前
    //$date = '2006-11-26 23:12:0';
    //echo LeaveNow($date, $now)."\n"; // 5秒前
    // }}}
    // {{{ MailTo($id, $mail_type='regchk', $ar_param='')
    /**
     * @brief 寄 Html Mail
     * @param id user name, get email by id
     * @param mail_type mail type: regchk...
     * @param ar_param array param
     * @return true/false
     */
    function sendMail($mailto, $subject, $content) {
        $headers  = "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=utf-8\n";
        $headers .= 'From: =?UTF-8?B?'.base64_encode('Joseph Jiang')."?= <josephj6802@gmail.com>\n";
        $subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
        return mail($mailto, $subject, $content, $headers);
    };
    // }}}

    function getUrl($url){
        $data = FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, CURL_TIMEOUT_SET);
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Sitetagbot/1.0; +http://sitetag.us)");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    };

    function htmlshow($str) {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    };

    // get from http://www.finalwebsites.com/snippets.php?id=42
    function start_session($expire = 0) {
        if ($expire == 0) {
            $expire = ini_get('session.gc_maxlifetime');
        } 
        else {
            ini_set('session.gc_maxlifetime', $expire);
        };
        if (empty($_COOKIE['PHPSESSID'])) {
            session_set_cookie_params($expire);
            session_start();
        } 
        else {
            session_start();
            setcookie('PHPSESSID', session_id(), time() + $expire);
        }
    };
    function conv_encode($str) {
        if (empty($str)) {
            return '';
        }
        return mb_convert_encoding($str, 'UTF-8', 'cp950, gb2312, utf-8');
    };
    function getErrorMsg($msg_id) {
        switch ($msg_id) {
            case -1:
                return 'Oops, system busy!';
                break;
            case -2:
                return 'Sorry, wrong URL';
                break;
            case -3:
                return 'Sorry, incorrect username format';
                break;
            case -4:
                return 'Sorry, the password must be more than 6 characters';
                break;
            case -5:
                return 'Sorry, incorrect email format';
                break;
            case -6:
                return 'Sorry, the title is too long (max limit 100)';
                break;
            case -7:
                return 'Sorry, the description is too long (max limit 230)';
                break;
            case -8:
                return 'Sorry, the username is not available';
                break;
            case -9:
                return 'Sorry, the password is not same with confirm password';
                break;
            case -10:
                return 'Sorry, wrong password';
                break;
            default:
                return 'Sorry, system busy!';
                break;
        }
    }

    function mc_delete($key, $ttl = 0)
    {
      $dbh = memcache_connect('localhost', 11211);
      if (!$dbh) return false;
      return memcache_delete($dbh, $key, $ttl);
    }

    function mc_fetch($key)
    {
      $dbh = memcache_connect('localhost', 11211);
      if (!$dbh) return false;
      return memcache_get($dbh, $key, null);
    }

    function mc_store($key, $var, $ttl = 0)
    {
      $dbh = memcache_connect('localhost', 11211);
      if (!$dbh) return false;
      return memcache_set($dbh, $key, $val, null, $ttl);
    }

    function TextC($n1,$n2) {
        $text = '壹,貳,參,肆,伍,陸,柒,捌,玖,拾';
        $text = split(',',$text);
        $n = rand($n1,$n2);
        $t = '';
        $total = 0;
        while ($total<$n) {
            $t .= $text[$total%count($text)];
            $total++;
        };
        return $t;
    };
    function Text($n1,$n2) {
        $text = "Lorem,Ipsum,is,simply,dummy,text,of,the,printing,and,typesetting,industry.,Lorem,Ipsum,has,been,the,industry's,standard,dummy,text,ever,since,the,1500s,,when,an,unknown,printer,took,a,galley,of,type,and,scrambl";
        $text = split(",",$text);
        $n = rand($n1,$n2);
        $t="";
        $i=0;
        while($i <  $n) {
            $t .= $text[ $i%count($text)    ]  . ' ';
            $i++;
        }
        return $t;
    }
    function Money($amount) {
        $amount = number_format($amount);
        return "NT$ " . $amount;
    }
    function insert($file_path) {
        echo "<!-- ". $file_path . " (start) -->\n";
        include($file_path) ;
        echo "<!-- ". $file_path . " (end) -->\n";
    }
    function getDateHTML(){
        return '2006-12-29';
    }
    function getTimeHTML(){
        return '2006-12-29 23:45:20';
    }
    function getPagingHTML(){
        $html = '';
		$html .= '<ul class="ykppaging">';
        $html .= '<li class="p"><a href="?" title="上一頁" class="pagelink">上一頁</a></li>';
        for($i=11;$i<21;$i++){
            $html .= '<li>';
            if($i==11){
                $html .= "<strong>$i</strong>";
            }
            else {
                $html .= "<a href=\"?tp=100&cp=$i\" class=\"pagelink\">$i</a>";
            }
            $html .= '</li>';
        };
        $html .= '<li class="n"><a href="?" ="下一頁" class="pagelink">下一頁</a></li>';
        $html .= '</ul>';
        return $html;
    }
    function getPagingHTML2(){
	    $html = '';
		$html .= '<ul class="ykppaging2">';
        $html .= '<li class="p"><a href="?" title="上一頁" class="pagelink">上一頁</a></li>';
        for($i=1;$i<10;$i++){
            $html .= '<li>';
            if($i==5){
                /*f is focused*/
                $html .= "<strong>$i</strong>";
            }
            else {
                $html .= "<a href=\"?tp=100&cp=$i\" class=\"pagelink\">$i</a>";
            }
            $html .= '</li>';
        };
        $html .= '<li class="n"><a href="?" ="下一頁" class="pagelink">下一頁</a></li>';
        $html .= '</ul>';
        return $html;
    }
    function getRankingStatusHTML($status,$rank) {
        $html = '';
        $statusTag = array("ins","span","del");
        $html .= '<' . $statusTag[$status] . '>' . $rank .  '</' . $statusTag[$status] . '>';
        return $html;
    }
	function getStarHTML($level) {
		$word = array('請評鑑','有待改進','勉強接受','還算不錯','非常喜歡','愛不釋手');
		return '<img src="'._IMGURL_.'ico_star_' . $level . '.gif" alt="' .$word[$level] . '">';	    
	}    
	function getAwardHTML($level) {
		$word = array('請評鑑','有待改進','勉強接受','還算不錯','非常喜歡','愛不釋手');
		return '<img src="'._IMGURL_.'ico_award_' . $level .'.gif" alt="' .$word[$level]. '">';	    
	};
	function getRelateLevelHTML($level) { 
		$words = array('高','很高','極高','非常高');
		return '<ins class="ykprl">( <img src="'._IMGURL.'ico_srp_level_' . $level . '.gif" alt="' . $words[$level-1] . '"/> 相似度：' . $words[$level-1] . '</span> )</ins>';	    
	}
    function deleteBlogComment($id) {
        $id = intval($id);
        if (!is_int($id)) {
            return false;
        };
        DeleteData('blog_comment', array('blog_comment_id' => $id));
        return true;
    };
    function hideBlogComment($id) {
        $id = intval($id);
        if (!is_int($id)) {
            return false;
        };
        UpdateData('blog_comment', array('is_show' => 'n'), array('blog_comment_id' => $id));
        return true;
    };
    function getPageHTML($total, $url, $page = 1, $page_size = 10) {
        // 將目前頁數的例外情況設定為 1
        if ($page < 1 || !is_numeric($page)) {
            $page = 1;
        };
        // 起始值 ex. 0, 10, 20
        $b = ($page - 1) * $page_size;
        // 取得總頁數
        $total_page = ceil($total / $page_size);
        $page_html = '<div class="paging">';
        // 產出前一頁
        if ($page - 1 > 0) {
            $previous_page = $page - 1;
            $page_html .= '<span class="p"><a href="' . $url . '?page=' . $previous_page .'" title="前一頁" class="pagelink"><span class="prev">前一頁</span></a></span>';
            if ($page < 10) {
                $first_page = 1;
            }
            else {
                $first_page = $page - 10;
            };
        }
        else {
            $first_page = 1;
        };
        // 算出最後一頁
        $last_page = ($first_page + $page_size) > $total_page ? $total_page : $first_page + $page_size - 1;
        // 頁數的部分
        for ($i = $first_page; $i <= $last_page; $i++) {
            $page_html .= '<span>';
            if ($page == $i) {
                $page_html .= '<strong>' . $i . '</strong>';
            } 
            else {
                $page_html .= '<a href="' . $url . '?page=' . $i . '" class="pagelink">' . $i . '</a>';
            };
            $page_html .= '</span>';
        };

        if ($page + 1 <= $total_page) {
            $next_page = $page + 1;
            $page_html .= '<span class="n"><a href="' . $url . '?page=' . $next_page . '" title="下一頁" class="pagelink">下一頁</a></span>';
        };
        $page_html .= '</div>';

        return $page_html;
    };
?>
