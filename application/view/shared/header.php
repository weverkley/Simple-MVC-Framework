<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $data['title']; ?></title>

    <!-- Bootstrap -->
    <link href="<?= BASE_URL ?>public/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= BASE_URL ?>public/libs/components-font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="<?= BASE_URL ?>public/js/html5shiv.min.js"></script>
      <script src="<?= BASE_URL ?>public/js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a target="_blank" class="navbar-brand" href="https://github.com/weverkley/Simple-MVC-Framework">Simple-MVC-Freamework</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                        $pages = array();
                        $pages["index"] = "Home";
                        $pages["home/about"] = "About";
                        foreach($pages as $link => $title) {
                            $current = (preg_match('/'.$this->action.'/', $link)) ? " class='active'" : "";
                            $addr = $link == 'index' ? BASE_URL : $link;
                            echo "<li{$current}><a href='{$addr}'>{$title}</a></li>";
                            unset($link);
                        }
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
