<%@ page session="true" contentType="text/html; charset=ISO-8859-1" %>
    <%@ taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %>
        <%@ taglib prefix="c" uri="http://java.sun.com/jstl/core" %>

            <jp:mondrianQuery id="query01" jdbcDriver="com.mysql.jdbc.Driver"
                jdbcUrl="jdbc:mysql://localhost/newadventurework?user=root&password="
                catalogUri="/WEB-INF/queries/dwpembelian.xml">

                select {[Measures].[SubTotal],[Measures].[TaxAmt],[Measures].[Freight]} ON COLUMNS,
                {([time],[purchasing],[vendor],[ShipMethod])} ON ROWS from [Pembelian]


            </jp:mondrianQuery>





            <c:set var="title01" scope="session">Query WH Adventure Fakta Pembelian using Mondrian OLAP</c:set>