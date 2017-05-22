$(function() {
	  var pageCount = 0;
	  var key = 'd52e52cea3e74f7995b2e58a735ce118';
		
		$("#nextPageButton").click(function() {	
			pageCount++;
			
			// empty all the div's
			$('#pageNumber').empty();
			for (var i = 0; i < 10; i++) {
				$('#newsItem'+i).empty();
			}
			
			// ajax call
			search(true);
		});

		$("#prevPageButton").click(function(){
			pageCount--;
			
			// empty all the div's
			$('#pageNumber').empty();
			for (var i = 0; i < 10; i++) {
				$('#newsItem'+i).empty();
			}
			
			search(true);
		});
		
		//--------------------------------------- Send http GET request --------------------------------------//
		function search(isPagingPressed){
			// get value of input fields
			var searchTerm = $("#searchTerm").val();
			var beginDate = $("#altBeginDate").val();
			var endDate = $("#altEndDate").val();
			
			// return if any of the search terms is empty
			if(searchTerm == '' || beginDate == '' || endDate == '')
			{
				 $("#newsItem0").empty();
				 $("#newsItem0").append("Input Error! Search requires \
										keyword and a range of dates!");
				return;
			}
			
			// set page count
			if(isPagingPressed){
				// prvevent pageCount from being negative
				if (pageCount < 0){
					pageCount = 0;
				} 
			}
			else
				// set to 0 to get to the 1st page when pressing search;
				pageCount = 0;

			// create query string
			var dataString = 'q='+searchTerm + '&page='+pageCount + 
			'&begin_date='+ beginDate +'&end_date='+endDate + 
			'&sort=newest' +'&api-key='+key;
			
			$.ajax({
				  type: "GET",
				  url: 'http://api.nytimes.com/svc/search/v2/articlesearch.json',
				  data: dataString,
				  
				  success: function(result, textStatus, xhr) {
					$('#pageNumber').empty();
					$('#pageNumberBottom').empty();
					for (var i = 0; i < 10; i++) {
						$('#newsItem'+i).empty();
					}

					var article = result.response.docs;
					for (var i = 0, len = article.length; i < len; i++) {
						var link = article[i].web_url;
						var headline = article[i].headline.main;
						$('#newsItem'+i).append('<a href='+ link +'>'+ headline +"</a>");
					}
					$('#pageNumber').append('page '+ (pageCount+1));
					$('#pageNumberBottom').append('page '+ (pageCount+1));
				  },//success

				error: function(xhr, textStatus, error){
					console.log('error'+ error);
					if (textStatus == "error") {
						 
						 $("#newsItem0").empty();
						 $("#newsItem0").append("Error: " + xhr.status + "<br>The error message was: " + xhr.statusText );
						 $("#newsItem5").empty()
						 $("#newsItem5").append("NYTimes API query limit is reached: 1 query/sec")

					}
				}//error
				
			});//ajax

		}//search
		

		$("#searchButton").click(function(){
			search(false);
		
		});//click button
		
		//---------------- Datepicker functions --------------------//
		$.datepicker.setDefaults({
		  showOn: "both",
		  buttonImageOnly: true,
		  buttonImage: "css/images/calendar-blue.png",
		  showAnim: 'slideDown',
		  duration: 'fast'
		});

		$("#beginDate").datepicker({
			dateFormat: 'mm/dd/yy',
			// change to NYT format yymmdd
			altFormat: "yymmdd",
			altField: "#altBeginDate"
		});
		
		$( "#endDate" ).datepicker({
			dateFormat: 'mm/dd/yy',
			altFormat: "yymmdd",
			altField: "#altEndDate"
		});
		
});