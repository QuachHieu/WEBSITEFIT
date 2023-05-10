<style type="text/css">
dl,
dt,
dd,
ol,
ul,
li {
  margin: 0;
  padding: 0;
  border: 0;
  font-size: 100%;
  font: inherit;
  vertical-align: baseline;
  list-style: none;
}

a {
  text-decoration: none;
}

li a.active{
  color: white;
  background: rgb(0,163,228);
}
.navv {
    width: 101%;
/*  margin-top: 150px;*/
  box-shadow: 0 3px 12px rgba(0, 0, 0, 0.25);
  margin-right: auto;
  margin-left: auto;
  max-width: 22.5rem;
}

.navv a,
.navv label {
  font-weight: normal;
  border-radius: 4px;
  display: block;
  padding: 20px;
  color: black;
  background-color: #f5f5f5;
  box-shadow: inset 0 -1px white;
  -webkit-transition: all .25s ease-in;
  transition: all .25s ease-in;
}

.navv a:focus,
.navv a:hover,
.navv label:focus,
.navv label:hover {
  color: white;
  background: rgb(0,163,228);
}

.navv label {
  cursor: pointer;
}

.item-list a,
.item-list label {
  font-weight: normal;
  padding-left: 2rem;
  background: #f5f5f5;
  box-shadow: inset 0 -1px white;
}

.item-list a:focus,
.item-list a:hover,
.item-list label:focus,
.item-list label:hover {
  background: rgb(0,163,228);
}

.sub-list a,
.sub-item-list label {
  padding-left: 4rem;
  background: #f5f5f5;
  box-shadow: inset 0 -1px white;
}

.sub-item-list a:focus,
.sub-item-list a:hover,
.sub-item-list label:focus,
.sub-item-list label:hover {
  background: rgb(0,163,228);
}
d
.sub-sub-item-list a,
.sub-sub-item-list label,
.sub-sub-sub-item-list a,
.sub-sub-sub-item-list label,
.sub-sub-sub-sub-item-list a,
.sub-sub-sub-sub-item-list label,
.sub-sub-sub-sub-sub-item-list a,
.sub-sub-sub-sub-sub-item-list label,
.sub-sub-sub-sub-sub-sub-item-list a,
.sub-sub-sub-sub-sub-sub-item-list label, {
  padding-left: 6rem;
  background: #454545;
  box-shadow: inset 0 -1px white;
}

.sub-sub-item-list a:focus,
.sub-sub-item-list a:hover,
.sub-sub-item-list label:focus,
.sub-sub-item-list label:hover,
.sub-sub-sub-item-list a:focus,
.sub-sub-sub-item-list a:hover,
.sub-sub-sub-item-list label:focus,
.sub-sub-sub-item-list label:hover,
.sub-sub-sub-sub-item-list a:focus,
.sub-sub-sub-sub-item-list a:hover,
.sub-sub-sub-sub-item-list label:focus,
.sub-sub-sub-sub-item-list label:hover,
.sub-sub-sub-sub-sub-item-list a:focus,
.sub-sub-sub-sub-sub-item-list a:hover,
.sub-sub-sub-sub-sub-item-list label:focus,
.sub-sub-sub-sub-sub-item-list label:hover,
.sub-sub-sub-sub-sub-sub-item-list a:focus,
.sub-sub-sub-sub-sub-sub-item-list a:hover,
.sub-sub-sub-sub-sub-sub-item-list label:focus,
.sub-sub-sub-sub-sub-sub-item-list label:hover, {
  background: rgb(0,163,228);
}

.item-list,
.sub-item-list,
.sub-sub-item-list,
.sub-sub-sub-item-list,
.sub-sub-sub-sub-item-list,
.sub-sub-sub-sub-sub-item-list,
.sub-sub-sub-sub-sub-item-list,
.sub-sub-sub-sub-sub-sub-item-list,
.sub-sub-sub-sub-sub-sub-sub-item-list {
  height: 100%;
  max-height: 0;
  overflow: hidden;
  -webkit-transition: max-height .5s ease-in-out;
  transition: max-height .5s ease-in-out;
}

.toggle input[type=checkbox]:checked + label + ul {
  max-height: 1000px;
}

label > span {
  float: right;
}

.toggle input[type=checkbox]:checked + label > span {
  transform: rotate(90deg);
}
</style>