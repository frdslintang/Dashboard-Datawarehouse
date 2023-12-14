<%@ page session="true" contentType="text/html; charset=ISO-8859-1" %>
    <%@ taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %>
        <%@ taglib prefix="c" uri="http://java.sun.com/jstl/core" %>

            <jp:mondrianQuery id="query01" jdbcDriver="com.mysql.jdbc.Driver"
                jdbcUrl="jdbc:mysql://localhost/newadventurework?user=root&password="
                catalogUri="/WEB-INF/queries/dwpermintaan.xml">

                select {[Measures].[MinOrderQty],[Measures].[MaxOrderQty],[Measures].[OnOrderQty]} ON COLUMNS,
                {([time],[product],[vendor],[ShipMethod])} ON ROWS from [Permintaan]


            </jp:mondrianQuery>





            <c:set var="title01" scope="session">Query WH Adventure Fakta Permintaan using Mondrian OLAP</c:set>