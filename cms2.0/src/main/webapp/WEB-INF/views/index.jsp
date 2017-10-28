<%@ page session="false"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt" %>

<%@ page language="java" contentType="text/html" pageEncoding="UTF-8"%>

<!DOCTYPE html>

<html>
	<head>
		<title>Kingdom CMS Home</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href='<c:url value="/css/main.css"/>' >
		<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href='<c:url value="/utils/font-awesome/css/font-awesome.min.css"/>' >
	</head>
	<body>
		<div id="test-message">Hello World! <c:out value="${message}"/></div>
		<div>Test Node => <c:out value="${node}"/></div>
		<div id="col1">
			<div id="navhead">
				<div id="search" class="nav-icon"><i class="fa fa-search" aria-hidden="true"></i></div>
				<div id="addnew" class="nav-icon"><i class="fa fa-plus" aria-hidden="true"></i></div>
				<div id="signin" class="nav-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
				<div id="logo"><span>Kingdo</span><span style="margin-left:-5px;">M</span></div>
			</div>
			<div id="searchbox">
				<input id="search-window search" style="display:none"><span id="search-go">go</span>
			</div>
		</div>
		<div id="col2">
			<div class="nav"></div>
		</div>
		<script src='<c:url value="/utils/jquery/jquery-min.js"/>'></script>
		<script src='<c:url value="/js/main.js"/>'></script>
	</body>
</html>