<?php
ob_start();
session_start();
session_regenerate_id();
//this for put random number for session id
//above  to start session
?>
<!doctype html>
<html dir="rtl">

<head>

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Adding favicon -->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

	<!-- Bootstrap CSS -->
	<link href="<?php echo $bootstrap_css; ?>" rel="stylesheet" />
	<link href="<?php echo $css; ?>backend.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo $font_awesome_css; ?>" type="text/css">

	<!-- jQuery_lib -->
	<script src="<?php echo $jQuery_js; ?>"></script>
	<!--custom jQuery code or functions -->
	<script src="<?php echo $js; ?>backend.js"></script>
	<!-- for lazy size img loading -->
	<script src="<?php echo $js; ?>lazysizes.min.js" async=""></script>

	<?php
	if (empty($pageTitle)) {
		$pageTitle =  lang('Maktabati');
	}
	?>

	<title><?php echo $pageTitle ?></title>
</head>

<body>
	<!-- this is header -->
	<!-- start navbar -->
	<nav class="navbar navbar-expand-md fixed-top navbar-light bg-white shadow">
		<div class="container">
			<a class="navbar-brand" href="index.php">
				<img src="img/logo.png" alt="Logo" style="width:40px;" class="bg-white img-fluid" />
			</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".m">
				<span class="navbar-toggler-icon"></span>
			</button>
			<?php
			//to specify active page of navbar
			if (empty($activePage)) {
				$activePage = 'index';
			}
			?>

			<div class="collapse navbar-collapse  m" id="#mynavbar">
				<ul class="navbar-nav nav-icon me-auto text-center">
					<li class="nav-item <?php $active = $activePage == "index" ? 'active' : ' ';
										echo $active; ?>">
						<a class="nav-link" href="index.php">
							<!-- try to add external svg file -->
							<span class="icon">
								<i class="fa-light fa-house" style="font-size: 23px; margin-right: -3px !important;"></i>
							</span>
							<p class="nav-text"><?php echo lang('home') ?></p>
						</a>
					</li>
					<li class="nav-item <?php $active = $activePage == "categories" ? 'active' : ' ';
										echo $active; ?>">
						<a class="nav-link" href="categories.php">
							<!-- try to add external svg file  -->
							<span class="icon">

								<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 469.333 469.333" style="enable-background:new 0 0 469.333 469.333;" xml:space="preserve">
									<g>
										<g>
											<path d="M458.667,0h-448C4.771,0,0,4.771,0,10.667v448c0,5.896,4.771,10.667,10.667,10.667h448
			c5.896,0,10.667-4.771,10.667-10.667v-448C469.333,4.771,464.563,0,458.667,0z M149.333,448h-128V170.667h128V448z M298.667,448
			h-128V170.667h128V448z M448,448H320V170.667h128V448z M448,149.333H21.333v-128H448V149.333z"></path>
										</g>
									</g>

								</svg>
							</span>
							<!-- <i class="fa fa-sitemap"></i> -->
							<p class="nav-text"><?php echo lang('categories') ?></p>
						</a>
					</li>
					<li class="nav-item <?php $active = $activePage == "authors" ? 'active' : ' ';
										echo $active; ?>">
						<a class="nav-link" href="authors.php">
							<!-- try to add external svg file  -->
							<span class="icon">
								<svg enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
									<g>
										<path d="m490.685 314.751c-9.172-12.021-20.956-21.936-34.243-28.987 7.099-12.131 11.171-26.239 11.171-41.279v-38.456c0-45.216-36.798-82.002-82.028-82.002-16.235 0-31.712 4.818-44.771 13.29v-77.396c-.001-33.041-26.881-59.921-59.921-59.921h-135.527v60.527c0 20.297 10.144 38.269 25.628 49.113v27.552c-13.018-8.395-28.423-13.165-44.578-13.165-45.23 0-82.028 36.786-82.028 82.002v38.456c0 15.041 4.072 29.148 11.171 41.279-13.288 7.05-25.072 16.966-34.243 28.986-13.945 18.277-21.316 40.114-21.316 63.152v134.098h512v-134.098c0-23.038-7.371-44.875-21.315-63.151zm-315.319-284.751h105.526c16.498 0 29.921 13.422 29.921 29.921v30.527h-105.526c-16.498 0-29.921-13.422-29.921-29.921zm135.447 90.448v40.639c0 30.262-24.632 54.882-54.909 54.882s-54.91-24.62-54.91-54.882v-40.792c1.418.101 2.85.152 4.293.152h105.526zm-199.164 361.552h-81.649v-104.098c0-30.197 18.607-57.407 46.102-68.695 10.204 7.945 22.328 13.536 35.548 15.948v156.845zm0-189.941v2.296c-21.519-6.377-37.262-26.32-37.262-49.869v-38.456c0-28.674 23.34-52.002 52.029-52.002 21.794 0 41.433 13.643 48.952 33.976 2.281 6.802 5.401 13.223 9.238 19.138-41.241 6.228-72.957 41.935-72.957 84.917zm129.255 189.941h-99.255v-189.941c0-27.673 20.197-50.712 46.61-55.119.351 10.471 1.259 22.983 3.238 36.559 6.904 47.369 23.941 86.469 49.407 113.573zm-22.561-244.796c11.331 5.61 24.084 8.766 37.562 8.766 13.493 0 26.26-3.163 37.601-8.785-1.198 31.964-8.082 84.105-37.597 121.399-29.442-37.245-36.342-89.44-37.566-121.38zm151.816 244.796h-99.255v-94.928c25.467-27.108 42.504-66.235 49.408-113.65 1.972-13.54 2.881-26.023 3.234-36.483 26.414 4.406 46.613 27.446 46.613 55.12zm-42.958-274.859c3.804-5.865 6.904-12.226 9.179-18.963l.164.06c7.454-20.463 27.161-34.211 49.04-34.211 28.688 0 52.028 23.328 52.028 52.002v38.456c0 23.619-15.836 43.611-37.453 49.925v-2.352c0-42.981-31.716-78.688-72.958-84.917zm154.799 274.859h-81.841v-156.809c13.294-2.393 25.486-8 35.74-15.983 27.494 11.287 46.101 38.498 46.101 68.694z"></path>
									</g>
								</svg>
							</span>
							<!-- <i class="fa fa-users"></i> -->
							<p class="nav-text"><?php echo lang('authors') ?></p>
						</a>
					</li>
					<li class="nav-item <?php $active = $activePage == "publishers" ? 'active' : ' ';
										echo $active; ?>">
						<a class="nav-link" href="publishers.php">
							<span class="icon">
								<i class="fa-light fa-building" style="font-size: 23px;"></i>
							</span>
							<p class="nav-text"><?php echo lang('publishers') ?></p>
						</a>
					</li>
					<li class="nav-item <?php $active = $activePage == "advance_search" ? 'active' : ' ';
										echo $active; ?>">
						<a class="nav-link" href="advance_search.php">
							<span class="icon">
								<i class="fa-light fa-magnifying-glass" style="font-size: 23px;"></i>
							</span>
							<p class="nav-text"><?php echo lang('search') ?></p>
						</a>
					</li>
				</ul>

				<?php
				//to change btn between login and logout
				//chick if  session is set 
				if (isset($_SESSION['UserEmail'])) {
					echo '<a href="" class="logoutbtn  btn btn-danger me-auto " data-bs-toggle="modal" data-bs-target="#logout_popup">' . lang("logout") . '</a>';
				} else {
				?>
					<a href="login.php" class="loginbtn btn me-auto"><?php echo lang('login') ?></a>
				<?php } ?>

			</div>

		</div>
	</nav>
	<!-- end navbar -->
	<!-- logout popup -->
	<div class="modal fade" id="logout_popup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered  ">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title  blue-text " id="staticBackdropLabel"><?php echo lang('logout_popup') ?></h5>
					<button type="button" class="btn-close m-0 " data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-footer  justify-content-evenly">
					<a href="logout.php" class="logout-popup btn"><?php echo lang('logout_Accept') ?></a>
					<button type="button" class="cancel btn btn-secondary" data-bs-dismiss="modal"><?php echo lang('cancel') ?></button>
				</div>
			</div>
		</div>
	</div>