<style type="text/css">
		/* CSS MENU */

		@import "https://fonts.googleapis.com/css?family=Montserrat";

		.menu {
			width: 280px;
			margin: 10px 0;
			cursor: pointer
		}

		.title {
			width: 101%;
			box-sizing: border-box;
			background: #fff;
			padding: 20px;
			border-radius: 4px;
			position: relative;
			/* box-shadow: 0 0 40px -10px #000; */
			border: 1px solid #e3e3e3;
			color: #fff;
			background: rgb(0,163,228);
		}

		.span {
			float: right;
			font-size: 18px !important
		}

		.dropdowntailieu {
			width: 100%;
			background: #F5F5F5;
			border-radius: 4px;
			border: 1px solid #e3e3e3;
			color: #505050;
			overflow: hidden;
			transition: all .5s
		}
		.c:hover {
			color: #fff;
		}
		.down {
			max-height: 100%;
		}

		.arrow {
			border-left: 10px solid transparent;
			border-right: 10px solid transparent;
			border-bottom: 10px solid #fff;
			position: absolute;
			right: 20px;
			bottom: -24px;
			display: none
		}

		.arrow.gone {
			display: block
		}

		.p {
			padding: 15px 14px;
			margin: 0;
			transition: all .1s
		}

		.p:hover {
			background: #00A3E4;
			-webkit-transform: scale(1.05);
			color: #fff;
			box-shadow: 0 0 30px -10px #000
		}



		/* ----------------------------------- */
		/* CSS CONTENT */
		.glyphicon {
			margin-right: 5px;
		}

		.thumbnail {
			margin-bottom: 20px;
			padding: 0px;
			-webkit-border-radius: 0px;
			-moz-border-radius: 0px;
			border-radius: 0px;
			display: block;
			padding: 4px;
			margin-bottom: 20px;
			line-height: 1.42857143;
			background-color: #fff;
			border: 1px solid #ddd;
			border-radius: 4px;
			-webkit-transition: border .4s ease-in-out;
			-o-transition: border .4s ease-in-out;
			transition: border .4s ease-in-out;
		}

		.well-sm {
			padding: 9px;
			border-radius: 3px;
		}

		.well {
			margin-top: 10px;
			min-height: 20px;
			padding: 19px;
			margin-bottom: 20px;
			background-color: #f5f5f5;
			border: 1px solid #e3e3e3;
			border-radius: 4px;
			-webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);
			box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);
		}

		.item.list-group-item {
			float: none;
			width: 100%;
			background-color: #fff;
			margin-bottom: 10px;
		}

		.item.list-group-item:nth-of-type(odd):hover,
		.item.list-group-item:hover {
			background: #428bca;
		}

		.item.list-group-item .list-group-image {
			margin-right: 10px;
		}

		.item.list-group-item .thumbnail {
			margin-bottom: 0px;
		}

		.item.list-group-item .caption {
			padding: 4px 4px 0px 4px;
		}

		.item.list-group-item:nth-of-type(odd) {
			background: #eeeeee;
		}

		.item.list-group-item:before,
		.item.list-group-item:after {
			display: table;
			content: " ";
		}

		.item.list-group-item img {
			float: left;
		}

		.item.list-group-item:after {
			clear: both;
		}

		.list-group-item-text {
			margin: 0 0 11px;
		}

		/* RESET RULES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
		:root {
			--black: #1a1a1a;
			--white: #fff;
			--gray: #ccc;
			--darkgreen: #18846C;
			--lightbrown: antiquewhite;
			--darkblack: rgba(0, 0, 0, 0.8);
			--minRangeValue: 280px;
		}

		* {
			margin: 0;
			padding: 0;
			outline: none;
			border: none;
		}

		button {
			cursor: pointer;
			background: none;
		}

		img {
			display: block;
			max-width: 100%;
			height: auto;
		}

		ol,
		ul {
			list-style: none;
		}

		a {
			color: inherit;
		}

		.gallery {
			padding: 0 2rem;
		}

		.container1 {
			max-width: 1030px;
			margin: 0 auto;
		}

		.d-none {
			display: none;
		}



		/* IMAGE LIST
–––––––––––––––––––––––––––––––––––––––––––––––––– */
		.image-list {
			margin: 3rem 0;
		}

		.image-list li {
			background: var(--lightbrown);
			color: var(--darkblack);
		}

		.image-list p:first-child {
			font-weight: bold;
			font-size: 1.15rem;
		}

		.image-list p:last-child {
			margin-top: 0.5rem;
		}


		/* GRID VIEW
–––––––––––––––––––––––––––––––––––––––––––––––––– */
		.grid-view {
			display: grid;
			grid-gap: 2rem;
			grid-template-columns: repeat(auto-fit, minmax(var(--minRangeValue), 1fr));
		}

		.grid-view figcaption {
			padding: 1rem;
		}

		/* MQ
–––––––––––––––––––––––––––––––––––––––––––––––––– */
		@media screen and (max-width: 900px) {
			.toolbar input[type="range"] {
				display: none;
			}
		}

		@media screen and (max-width: 700px) {
			.grid-view li {
				text-align: center;
				padding: 0.5rem;
			}

			.grid-view figcaption {
				padding: 0.5rem 0 0;
			}
		}
	</style>
	<script>
		// JS MENU
	
		/////////////////////////////////////
		$(document).ready(function () {
			$('#list').click(function (event) {
				event.preventDefault();
				$('#products .item').addClass('list-group-item');
			});
			$('#grid').click(function (event) {
				event.preventDefault();
				$('#products .item').removeClass('list-group-item');
				$('#products .item').addClass('grid-group-item');
			});
		});
	</script>