<?php

/**
 * Listing My Directory
 *
 * Free PHP directory lister
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019 Sri Aspari
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * @package	Listing My Directory
 * @author	Sri Aspari (siarie)
 * @link	https://github.com/listmydir
 * @since	Version 1.0.0
 **/

/*** CONFIGURATION ***/

/*
|---------------------------------------------------
| Site Title
|---------------------------------------------------
| This option will show title in the address bar and at
| the top of your page.
|
| NOTICE: YOU CAN SET NULL TO LEAVE IT BLANK
*/
$config['site_title']		= 'Local CDN';
/*
|---------------------------------------------------
| Root Directory
|---------------------------------------------------
| The top level directory where this script is located, 
| or alternatively one of it's sub-directories
|
| '.' Or '' => Directory where this script is located
| 'path'	=> Show sub directory
*/
$config['root_directory']	= '.';
/*
|---------------------------------------------------
| Directory URL
|---------------------------------------------------
| URL to Root Directory you want to listing
|
| http://example.com/
| OR
| http://example.com/path/
|
| WARNING: You MUST set this value!
*/
$config['directory_url']	= 'http://cdn.local.dep/';
/*
|---------------------------------------------------
| Open File in New Tab
|---------------------------------------------------
| If you would like open file in new browser tab,
| you must enable it by setting this variable value to
| TRUE (boolean).
*/
$config['open_new_tab']		= FALSE;
/*
|---------------------------------------------------
| Ignore Directory and Files
|---------------------------------------------------
|	This option will hide directory or files you want 
| hide in the directory listing
|
| hide_directories	Use to hide directory
| hide_files				Use to hide files
| hide_file_ext			Use to hide files by extension
*/
$config['hide_directories']	= array();
$config['hide_files']		= array();
$config['hide_files_ext']	= array();
/*
|---------------------------------------------------
| Sorting
|---------------------------------------------------
|  name_asc		sorting by name ascending
|  name_desc	sorting by name descending
|  type_asc		sorting by name ascending
|  type_desc	sorting by name descending
*/
$config['sort_by']			= 'name_asc';


require_once './Listmydir.php';
$start = new Listmydir();
$files = $start->index();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Local CDN</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		html {
			font-family: monospace;
		}

		body {
			width: 100%;
			max-height: 100vh;
			background: #F7F7F7;
		}

		.box {
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: 50%;
			height: 70vh;
			margin: auto;
			box-shadow: 0 4px 1px rgba(0, 0, 0, .05);
			border-radius: 5px;
			background: #FFFFFF;
			display: flex;
			flex-direction: column;
		}

		.box-header {
			text-align: center;
			text-transform: uppercase;
			border-bottom: 1px solid #F7F7F7;
			padding: 20px;
			/* flex: 1; */
		}

		.box-body {
			padding: 20px;
			overflow-y: auto;
			scrollbar-width: thin;
			scrollbar-color: grey #FFFFFF;
			flex: 1;
		}

		.breadcrumb {
			height: auto;
			border-bottom: 1px solid #F7F7F7;
		}

		/* .breadcrumb>ul>li>a {
			padding: 5px;
			background: #DC143C;
			color: #FFFFFF
		} */

		.box-footer {
			border-top: 1px solid #F7F7F7;
			text-align: center;
			padding: 20px;
			font-size: 10px;
			color: #555555;
		}

		table {
			width: 100%;
			border-collapse: collapse;
		}

		tr {
			background: #FFFFFF;

		}

		tr:hover {
			background: #00000008;
		}

		td {
			padding: 5px 2px;
		}

		a {
			text-decoration: none;
			color: #555555;
		}

		a:hover {
			color: #DC143C;
		}

		ul li {
			list-style: none;
			display: inline;
		}

		ul li::after {
			content: '/';
		}

		ul li:last-child::after {
			content: '';
		}
	</style>
</head>

<body>
	<div class="box">
		<div class="box-header">
			Local CDN <br>
		</div>
		<div class="breadcrumb">
			<?php if (!empty($files['breadcrumb'])) : ?>
				<ul>
					<?php foreach ($files['breadcrumb'] as $url => $name) : ?>

						<li>
							<?php
									$lastitem = array_keys($files['breadcrumb']);
									if ($url == end($lastitem)) : ?>
								<a href="?url=<?= $url ?>"><?= $name ?></a>
							<?php else : ?>
								<a href="?url=<?= $url ?>"><?= $name ?></a>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<div class="box-body">

			<table>
				<?php if (!empty($files['files'])) : ?>
					<?php foreach ($files['files'] as $file) : ?>
						<tr>
							<td>
								<a href="<?= $file['url'] ?>" target="<?= $file['target'] ?>"><?= $file['name'] ?></a>
							</td>
							<td align="right"><?= $file['type'] ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td>File Not Found</td>
					</tr>
				<?php endif; ?>
			</table>
		</div>

		<div class="box-footer">
			<p>&copy; 2019 SIARIE</p>
		</div>
	</div>
</body>

</html>