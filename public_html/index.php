<?php

require_once 'Results.class.php';

$url = 'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/front_page/rss.xml';
$Results = new \Deirde\BbcNewsWithLikes\Results($url);

?>

<html>
    <head>
        <title>
            <?php echo $Results->getPageTitle(); ?>
        </title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/assets/main.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <script src="/assets/main.js"></script>
    </head>
    <body>
        <div class="row">
            <div class="container">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="/">
                                <?php echo $Results->getPageTitle(); ?>
                            </a>
                        </div>
                    </div>
                </nav>
                <div class="col-md-12">
                    <div class="jumbotron">
                        <h1>
                            <?php echo $Results->channel['title']; ?>
                        </h1>
                        <p>
                            <a href="<?php echo $Results->channel['link']; ?>"
                                title="<?php echo $Results->channel['link']; ?>"
                                target="_blank">
                                <?php echo $Results->channel['link']; ?>
                            </a>
                        </p>
                        <p>
                            <?php echo _('Lorem ipsum dolor sit amet, 
                                consectetur adipiscing elit,  sed do eiusmod 
                                tempor incididunt ut labore et dolore magna
                                aliqua. Ut enim ad minim veniam, quis nostrud
                                exercitation ullamco laboris nisi ut aliquip ex
                                ea commodo consequat. Duis aute irure dolor in
                                reprehenderit in voluptate velit esse cillum
                                dolore eu fugiat nulla pariatur. Excepteur sint
                                occaecat cupidatat non proident, sunt in culpa
                                qui officia deserunt mollit anim id est laborum.'); ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul id="items-wrapper">
                        <form id="likes" method="POST">
                            <?php foreach ($Results->items as $item) { ?>
                                <li class="item">
                                    <h2 class="item-heading">
                                        <?php echo $item->title; ?>
                                    </h2>
                                    <p>
                                        <?php echo $item->description; ?>
                                        <a href="<?php echo $item->link; ?>"
                                            title="<?php echo $item->title; ?>" 
                                            target="_blank">
                                            <?php echo _('more'); ?>
                                        </a>
                                    </p>
                                    <p>
                                        <b>
                                            <?php echo _('Likes') . ': '; ?>
                                        </b>
                                        <span id="<?php echo $item->link; ?>"
                                            class="likes">
                                            <?php echo $item->likes; ?>
                                        </span>
                                    </p>
                                    <p class="bulk-like">
                                        <input type="checkbox" name="likes[<?php echo $item->link; ?>]" />
                                        <?php echo _('Vote'); ?>
                                    </p>
                                    <p class="submit hidden">
                                        <button type="submit" class="btn btn-sm btn-default">
                                            <?php echo _('Submit all your votes'); ?>
                                        </button>
                                    </p>
                                </li>
                            <?php } ?>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>
