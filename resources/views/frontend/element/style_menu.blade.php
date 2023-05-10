<style type="text/css">
* {
font-family:sans-serif;
list-style:none;
text-decoration:none;
margin:0;
padding:0;
}
.newa{
padding: 10px 12px !important;
font-weight: 700 !important;
text-transform: uppercase !important;
}
.nav > li {
float:left;
}

.nav li a {
background:#00A3E4;
color:#FFF;
display:block;
border:0px solid;
padding:10px 12px;
}

.nav li .down{
font-size: 9px;
padding-left: 6px;
display: none;
}

.nav li a:not(:last-child) .down {
display: inline;
}

.nav li a:hover {
background:#39b54a;
}

.nav li {
position:relative;
}

.nav li ul {
display:none;
position:absolute;
min-width:140px;
}

.nav li:hover > ul {
display:block;
}

.nav li ul li ul {
right:-140px;
top:0;
}
ul.nav {
  z-index: 10;
  display: block;
}
.navbarr {
    width: 1170px;
    font-size: 16px;
}
.navbarr {
    clear: both;
    display: block;
/*    width: 1200px;*/
    margin: 0 auto;
    padding: 0;
    color: #FFF;
}
</style>