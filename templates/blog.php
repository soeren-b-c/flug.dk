<?php
$articles = $blog->getArticles();
?>
<?php echo $header; ?>
<div id="blog-contents">
    <div id="blog-container">
        <?php 
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
                </div>
                <h2>
                    <a href="#" title="#"><?php echo $article->title; ?></a>
                </h2>
                <p class="meta-post">
                    Jimmi Kristensen 
                    <?php echo $created; ?>
                </p>
                <div class="entry">
                    <p>
                        <?php echo $doc; ?>
                        Patience is one tough lady: after 13 years in development, free 2D vector-based animation application Synfig Studio finally gets the golden badge of v1.0, delivering a sleigh of improvements and new features.
                    </p>
                    <span class="read-more">
                        <a class="link-btn1" href="#">Vis mere</a>
                    </span>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php echo $footer; ?>