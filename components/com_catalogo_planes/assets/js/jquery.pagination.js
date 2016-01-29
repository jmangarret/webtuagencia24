/**
* tablePagination - A table plugin for jQuery that creates pagination elements
*
* http://neoalchemy.org/tablePagination.html
*
* Copyright (c) 2009 Ryan Zielke (neoalchemy.com)
* Dual licensed under the MIT and GPL licenses:
* http://www.opensource.org/licenses/mit-license.php
*
* @name tablePagination
* @type jQuery
* @param Object settings;
*      firstArrow - Image - Pass in an image to replace default image. Default: (new Image()).src="./images/first.gif"
*      prevArrow - Image - Pass in an image to replace default image. Default: (new Image()).src="./images/prev.gif"
*      lastArrow - Image - Pass in an image to replace default image. Default: (new Image()).src="./images/last.gif"
*      nextArrow - Image - Pass in an image to replace default image. Default: (new Image()).src="./images/next.gif"
*      rowsPerPage - Number - used to determine the starting rows per page. Default: 5
*      currPage - Number - This is to determine what the starting current page is. Default: 1
*      optionsForRows - Array - This is to set the values on the rows per page. Default: [5,10,25,50,100]
*      ignoreRows - Array - This is to specify which 'tr' rows to ignore. It is recommended that you have those rows be invisible as they will mess with page counts. Default: []
*
*
* @author Ryan Zielke (neoalchemy.org)
* @version 0.1.0
* @requires jQuery v1.2.3 or above
*/

/*
Modify by: Andres Mora
Version: 1.0.0.2
Enterprise: Amadeus LSC 
09 May 2012
*/



(function ($) {
    var sufix = "";
    $.fn.tablePagination = function (settings) {
    	
        var defaults = {
            firstArrow: "first",
            firstArrowTitle: "first",
            prevArrow: "prev",
            prevArrowTitle: "prev",
            lastArrow: "last",
            lastArrowTitle: "last",
            nextArrow: "next",
            nextArrowTitle: "next",
            rowsPerPage: 5,
            currPage: 1,
            optionsForRows: [5, 10, 25, 50, 100],
            ignoreRows: [],
            ControlMessageEmpty: "",
            childCall:"div.fila_resultado_planes"
        };
        settings = $.extend(defaults, settings);
        
        return this.each(function () {
        	
            var table = $(this)[0];
            var prevPage = 1;
            sufix = table.id;
            
            //Tomo la url y la divido por la seccion que me define el numero de pagina
            var stringUrl = window.location.toString();
            var urlParts = stringUrl.split("#");
            
            //Valido si existen parametros
            if(urlParts.length>1){
            	var variables = urlParts[1].split("&");
            	for (i = 0; i < variables.length; i++) {
            	       Separ = variables[i].split("=");
            	       eval ('var '+Separ[0]+'="'+Separ[1]+'"');
            	}
            	/*if(page!=null && page!="" ){
            		settings.currPage = page;
            	} */           	
            }

            var totalPagesId = '#' + table.id + '+#tablePagination' + sufix + ' #tablePagination_totalPages' + sufix;
            var currPageId = '#' + table.id + '+#tablePagination' + sufix + ' #tablePagination_currPage' + sufix;
            var rowsPerPageId = '#' + table.id + '+#tablePagination' + sufix + ' #tablePagination_rowsPerPage' + sufix;
            var firstPageId = '#' + table.id + '+#tablePagination' + sufix + ' #tablePagination_firstPage' + sufix;
            var prevPageId = '#' + table.id + '+#tablePagination' + sufix + ' #tablePagination_prevPage' + sufix;
            var nextPageId = '#' + table.id + '+#tablePagination' + sufix + ' #tablePagination_nextPage' + sufix;
            var lastPageId = '#' + table.id + '+#tablePagination' + sufix + ' #tablePagination_lastPage' + sufix;
            var LinkData = '#' + table.id + '+#tablePagination' + sufix + ' #tablePagination_LinkPage' + sufix;

            /*
            * This line was neccesary because we need to reset paginator every time is created,
            * because  we need to integrate this with dynamic filters (picnet.table.filter.full). Juan Pablo Robin
            */
            if ($("#tablePagination_paginater" + sufix).lenght != 0)
                $("#tablePagination_paginater" + sufix).remove();
            
            var possibleTableRows = $.makeArray($(settings.childCall, table));
            
            var tableRows = $.grep(possibleTableRows, function (value, index) {
                return ($.inArray(value, defaults.ignoreRows) == -1);
            }, false)
            

            /*
            * Add validation for none table rows to remove paginator and add a message. Juan Pablo Robin
            */
            if (tableRows <= 0) {
                $("#tablePagination_paginater" + sufix).remove();

                if ($(GetNameError()) != null)
                    $(GetNameError()).show();

                return;
            }
            else {

                if ($(GetNameError()) != null)
                    $(GetNameError()).hide();

            }

            var numRows = tableRows.length
            var totalPages = resetTotalPages();
            var currPageNumber = (defaults.currPage > totalPages) ? 1 : defaults.currPage;
            if ($.inArray(defaults.rowsPerPage, defaults.optionsForRows) == -1)
                defaults.optionsForRows.push(defaults.rowsPerPage);


            function GetNameError() {
                var indexControl = table.id.lastIndexOf("_");
                return "#tableEmtpyMessage" + table.id.substring(indexControl);
            }


            function hideOtherPages(pageNum) {
                if (pageNum == 0 || pageNum > totalPages)
                    return;                
                var startIndex = (pageNum - 1) * defaults.rowsPerPage;
                var endIndex = (startIndex + parseInt(defaults.rowsPerPage) - 1);
                //alert(pageNum + " " + startIndex + " " + endIndex);
                $(tableRows).show();
                for (var i = 0; i < tableRows.length; i++) {
                    if (i < startIndex || i > endIndex) {
                        $(tableRows[i]).hide()
                    }
                }
            }

            function resetTotalPages() {
                var preTotalPages = Math.round(numRows / defaults.rowsPerPage);
                var totalPages = (preTotalPages * defaults.rowsPerPage < numRows) ? preTotalPages + 1 : preTotalPages;
                if ($(totalPagesId).length > 0)
                    $(totalPagesId).html(totalPages);
                return totalPages;
            }

            function resetCurrentPage(currPageNum) {
                //alert(currPageNum);
            	if (currPageNum > totalPages)
            		return false;
                if (currPageNum < 1 || currPageNum > totalPages)
                    return;
                
                currPageNumber = currPageNum;
                hideOtherPages(currPageNumber);
                $(currPageId).val(currPageNumber);
                
                if(prevPage!=currPageNumber){
                	$('html,body').animate({
                        scrollTop: $("#scrollToHere").offset().top
                    }, 500);                	
                }
                prevPage=currPageNumber;


                //codigo para marcar la pagina actual
                //$("#tablePagination_pagins" + sufix + " > a").css("font-weight", "normal");
                $("#tablePagination_pagins" + sufix + " > a").removeClass("page_active");                
                //$("#tablePagination_LinkPage" + sufix + currPageNum).css("font-weight", "bold");
                $("#tablePagination_LinkPage" + sufix + currPageNum).addClass("page_active");
                if (currPageNum == totalPages){
                	$(nextPageId).addClass('navPagoff');
                	$(lastPageId).addClass('navPagoff'); 
                	$(prevPageId).removeClass('navPagoff');
                	$(firstPageId).removeClass('navPagoff');                	
            		return false;
                }
                else{
                	$(nextPageId).removeClass('navPagoff');
                	$(lastPageId).removeClass('navPagoff');                	
                }
                if (currPageNum == 1){
                	$(prevPageId).addClass('navPagoff');
                	$(firstPageId).addClass('navPagoff');
                }else{
                	$(prevPageId).removeClass('navPagoff');
                	$(firstPageId).removeClass('navPagoff');              	
                }
            }

            function resetPerPageValues() {
                var isRowsPerPageMatched = false;
                var optsPerPage = defaults.optionsForRows;
                optsPerPage.sort();
               
                if (!isRowsPerPageMatched) {
                    defaults.optionsForRows == optsPerPage[0];
                }
            }

            function resetPages() {
                var htmlBuffer = [];
                for (a = 1; a < totalPages + 1; a++) {
                    htmlBuffer.push("&nbsp;<a id='tablePagination_LinkPage" + sufix + "" + a + "'  href='javascript: void(0)' >" + a + "</a>&nbsp;");
                }
                $("#tablePagination_pagins" + sufix).html("");
                $("#tablePagination_pagins" + sufix).append(htmlBuffer.join("").toString());
            }

            function createPaginationElements() {
                var htmlBuffer = [];                
                $("#tablePagination" + sufix).html("");                
                if(totalPages>1){
                	htmlBuffer.push("<div id='tablePagination" + sufix + "' style='text-align:center; margin-top:5px;'>");                
                    htmlBuffer.push("<span id='tablePagination_paginater" + sufix + "'>");
                    htmlBuffer.push("<a href='javascript:void(0)' title='" + settings.firstArrowTitle + "' id='tablePagination_firstPage" + sufix + "' class='navPagination navPagoff' >" + settings.firstArrow + "</a>");
                    htmlBuffer.push("<a href='javascript:void(0)' title='" + settings.prevArrowTitle + "' id='tablePagination_prevPage" + sufix + "' class='navPagination navPagoff'>" + settings.prevArrow + "</a>");
                    //htmlBuffer.push("Pagina");
                    //htmlBuffer.push("<input id='tablePagination_currPage' type='input' value='"+currPageNumber+"' size='1'>");

                    htmlBuffer.push("<span id='tablePagination_pagins" + sufix + "'>");
                    
                    //Add link list Andres Mora
                    for (a = 1; a < totalPages + 1; a++) {
                        htmlBuffer.push("&nbsp;<a id='tablePagination_LinkPage" + sufix + "" + a + "'  href='"+urlParts[0]+"#page="+a+"' >" + a + "</a>&nbsp;");
                    }
                    htmlBuffer.push("</span>");

                    //htmlBuffer.push("de <span id='tablePagination_totalPages" + sufix + "'>" + totalPages + "</span>");
                    htmlBuffer.push("<a href='javascript:void(0)' title='" + settings.nextArrowTitle + "' id='tablePagination_nextPage" + sufix + "' class='navPagination'>" + settings.nextArrow + "</a>");
                    htmlBuffer.push("<a href='javascript:void(0)' title='" + settings.lastArrowTitle + "' id='tablePagination_lastPage" + sufix + "' class='navPagination'>" + settings.lastArrow + "</a>");
                    htmlBuffer.push("</span>");
                    htmlBuffer.push("</div>");
                }                
                return htmlBuffer.join("").toString();
            }

            if ($(totalPagesId).length == 0) {
                $(this).after(createPaginationElements());
            }
            else {
                $('#tablePagination_currPage' + sufix).val(currPageNumber);
            }
            resetPerPageValues();
            hideOtherPages(currPageNumber);

            $(firstPageId).bind('click', function (e) {
            	
                resetCurrentPage(1)
            });

            $(prevPageId).bind('click', function (e) {
                resetCurrentPage(currPageNumber - 1)
            });

            $(nextPageId).bind('click', function (e) {
                resetCurrentPage(currPageNumber + 1)
                                
            });

            $(lastPageId).bind('click', function (e) {
                resetCurrentPage(totalPages)
            });

            $(currPageId).bind('change', function (e) {
                resetCurrentPage(this.value)
            });

            $(rowsPerPageId).bind('change', function (e) {
                defaults.rowsPerPage = parseInt(this.value, 10);
                totalPages = resetTotalPages();
                resetCurrentPage(1);
                resetPages();
                setActionToPage();
                //$("#tablePagination_LinkPage" + sufix + settings.currPage).css("font-weight", "bold");
                $("#tablePagination_LinkPage" + sufix + settings.currPage).addClass("page_active");
            });

            function setActionToPage() {
                for (a = 1; a < totalPages + 1; a++) {
                    $(LinkData + a).bind('click', function (e) {
                        //No hay necesidad del element con el this se referencia
                        myval = parseInt($(this).text());
                        
                        resetCurrentPage(myval);
                    });
                }
            }

            setActionToPage();
            //Se marca la pagina por defecto
            //$("#tablePagination_LinkPage" + sufix + settings.currPage).css("font-weight", "bold");
            $("#tablePagination_LinkPage" + sufix + settings.currPage).addClass("page_active");
        })
    };
})(jQuery);