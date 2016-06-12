<?php

require_once 'Results.class.php';
require_once 'Votes.class.php';
$url = 'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/front_page/rss.xml';

$Results = new \Deirde\BbcNewsWithVotes\Results($url);
$Votes = new \Deirde\BbcNewsWithVotes\Votes($_REQUEST);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            <?php echo $Results->pageTitle; ?>
        </title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/assets/main.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <script type="text/javascript">
            var flash = '<?php echo ((isset($_SESSION['flash'])) ? "ok" : "ko"); ?>';
        </script>
        <script type="text/javascript" src="/assets/main.js"></script>
    </head>
    <body>
        <div id="wrapper" class="row">
            <div class="container">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="/">
                                <?php echo $Results->pageTitle; ?>
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
                    <form id="votes" method="POST">
                        <ul id="items-wrapper">
                            <?php foreach ($Results->items as $item) { ?>
                                <li class="item col-md-3">
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
                                            <?php echo _('Votes') . ': '; ?>
                                        </b>
                                        <span id="<?php echo $item->link; ?>"
                                            class="votes">
                                            <?php echo $item->votes; ?>
                                        </span>
                                    </p>
                                    <p class="bulk-vote">
                                        <input type="checkbox" name="votes[<?php echo $item->link; ?>]" />
                                        <?php echo _('Vote'); ?>
                                    </p>
                                    <p class="submit not-visible">
                                        <button type="submit" class="btn btn-sm btn-default xhr">
                                            <?php echo _('XHR post your votes'); ?>
                                        </button>
                                        <button type="submit" class="btn btn-sm btn-default post">
                                            <?php echo _('Post your votes'); ?>
                                        </button>
                                    </p>
                                </li>
                            <?php } ?>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
        <div id="modal">
            <p>
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                <?php echo _('Your votes has been signed. Thanks!'); ?>
            </p>
            <p>
                <button id="modal-close" class="btn btn-sm btn-info">
                    <?php echo _('Close or click outside the modal'); ?>
                </button>
            </p>
        </div>
        <?php unset($_SESSION['flash']); ?>
    </body>
</html>
