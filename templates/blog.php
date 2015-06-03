<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="Fyns Linux User Group">
        <link href='http://fonts.googleapis.com/css?family=Mandali|Ubuntu:300|Oxygen+Mono|Ubuntu+Mono' rel='stylesheet' type='text/css'>
        <link href="styles/main.css" media="all" type="text/css" rel="stylesheet"/>
        <script src="/js/jquery-1.11.3.min.js"></script>
        
        <link rel="stylesheet" href="/styles/highlightjs/github.css" type="text/css"/>
        <link rel="stylesheet" href="/styles/asciidoc/default.css" type="text/css"/>
        <script src="/js/highlight.pack.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
        <script src="/js/common.js"></script>

        <title></title>
    </head>
    <body>
<?php
$articles = $blog->getArticles();
?>
        <header id="header-area">
            <div id="header-container">
                <h1>
                    <a href="/" class="home-link">flug.dk</a>
                </h1>
                <nav id="navigation">
                    <?php echo $searchField; ?>
                    <a href="/blog">Blog</a>
                    <a href="/#2" class="event-link">Events</a>
                    <a href="/#3" class="who-link">Hvem er flug?</a>
                </nav>
            </div>
        </header>
        <div id="blog-contents">
            <div id="blog-container">
                <div id="spacer"></div>
                <?php 
                if ($articles != null) {
                foreach ($articles->asciidoc as $article) { 
                    $date = new DateTime($article->created);
                    $created = $date->format('Y-m-d H:i:s');
                    $doc = $blog->getArticle($article->title);
                ?>
                <div class="box">
                    <div class="post">
                        <a href="#" title="#">
                            <img class="thumbnail alignleft" src="assets/default_thumb.png" alt="#"/>
                        </a>
                        <div class="text">
                            <p class="meta">
                                <span class="cat">
                                    <?php 
                                    $numCat = count($article->categories);
                                    $i = 1;
                                    foreach ($article->categories as $cat) {
                                    ?>
                                    <a title="" href="#"><?php echo $cat->name; ?></a>
                                    <?php 
                                        if ($i < $numCat) {
                                    ?>
                                     / 
                                    <?php
                                        }
                                        $i++;
                                    } 
                                    ?>
                                </span>
                            </p>
                            <h2>
                                <a href="#" title="#"><?php echo $article->title; ?></a>
                            </h2>
                            <p class="meta-post">
                                Jimmi Kristensen 
                                <?php echo $created; ?>
                            </p>
                            <div class="entry">
                                <?php echo $doc; ?>
                                <p>Patience is one tough lady: after 13 years in development, free 2D vector-based animation application Synfig Studio finally gets the golden badge of v1.0, delivering a sleigh of improvements and new features.</p>
                                <div class="box-end"></div>
                                <span class="read-more">
                                    <a class="link-btn1" href="#">Vis mere</a>
                                </span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <?php 
                }
                }
                ?>
            </div>
            <footer id="footer">
                <div id="footer-container">
                    <div id="footer-nav">
                        <h4>Navigation</h4>
                        <ul id="site-links" class="list-line">
                            <li><a class="home-link" href="/">flug.dk</a></li>
                            <li>/</li>
                            <li><a href="/blog">Blog</a></li>
                            <li>/</li>
                            <li><a class="event-link" href="/#2">Events</a></li>
                            <li>/</li>
                            <li><a class="who-link" href="/#3">Hvem er flug?</a></li>
                        </ul>
                    </div>
                    <div id="footer-findus">
                        <h4>Find os</h4>
                        <ul id="social-links" class="list-line">
                            <li>
                                <a href="https://www.facebook.com/groups/flug.dk" target="_blank">
                                    <img height="30" src="/assets/fb_icon.png" alt="FB"/>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/groups/flug.dk" target="_blank">
                                    Facebook
                                </a>
                            </li>
                            <li>
                                <a href="https://plus.google.com/communities/105454146240712904444" target="_blank">
                                    <img height="30" src="/assets/googleplus_icon.png" alt="GP"/>
                                </a>
                            </li>
                            <li>
                                <a href="https://plus.google.com/communities/105454146240712904444" target="_blank">
                                    Google+
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>