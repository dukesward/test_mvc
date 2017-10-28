<%@ page session="false"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt" %>

<%@ page language="java" contentType="text/html" pageEncoding="UTF-8"%>

<!DOCTYPE html>

<head>
	<title>Kingdom CMS Test Page</title>
	<meta http-equiv="Content-type" content="text/html" charset="utf-8"></meta>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href='<c:url value="/css/test.css"/>' >
	<link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow" rel="stylesheet">
	<link rel="stylesheet" href='<c:url value="/utils/font-awesome/css/font-awesome.min.css"/>' >
</head>
<body>
	<div id='cms_test_name' class='cms_blocker_main'><span class='cms_head'>Test Module</span> <c:out value="${name}"/></div>
	<div id='cms_test_log'>
		<div class='cms_blocker_main'><span class='cms_head'>Start Testing</span> ${date}</div>
		<c:if test='${exception_test}'>
			<div class='cms_test_error'>
				<div>The testing stops because of error:</div>
				<div>${exception}</div>
			</div>
		</c:if>
		<c:if test='${code != null}'>
			<div class='cms_test_result cms_blocker_main'><span class='cms_head'>Testing Code</span> ${code}</div>
		</c:if>
		<c:if test='${log != null}'>
			<div class='cms_test_log cms_blocker_main'><span class='cms_head'>Testing Log</span></div>
		</c:if>
	</div>
</body>