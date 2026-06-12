<script type="text/javascript">

$('document').ready(function(){


  var dataArray = [];
  var mtdArray = [];
  var mtdValueArray = [];
  var month = [];
  var roomNight = [];
  var budgetNight = [];
  var roomNightValue = [];
  var budgetNightValue = [];
  var mtdArray =[];
  var mtdPerValueArray =[];
   var item_description =[];
   var sumtotal=[];
  //var mtdRnValueThis = [];
  //var mtdRoomNightThis = [];
  //var mtdRnValueLast = [];
  //var mtdRoomNightLast = [];
  
 
    dataArray = <?php echo json_encode($finalData); ?>;
    if(dataArray != "" && dataArray != null){
      for(var i =0 ; i < dataArray.length ;i++ ){
        for(var j=0 ; j < 1 ;j++){
          month.push(dataArray[i][0]);

          if(dataArray[i][1]==null)
            roomNight.push(0);
          else
            roomNight.push(dataArray[i][1]);
          
          budgetNight.push(dataArray[i][2]);
          
          if(dataArray[i][3]==null)
            roomNightValue.push(0);
          else
           roomNightValue.push(dataArray[i][3]);
          
          if(dataArray[i][4]==null)
            budgetNightValue.push(0);
          else
            budgetNightValue .push(dataArray[i][4]);
          

          }
      }
    }

  

  mtdArray = <?php echo json_encode($mtdData);?>;
  mtdValueArray = <?php echo json_encode($mtdDataVal);?>;
  mtdPerValueArray = <?php echo json_encode($mtdPerResultData);?>;
  
  
   if(mtdPerValueArray != "" && mtdPerValueArray != null){
	   alert(mtdPerValueArray.item_description.length);
      for(var k =0 ; k < mtdPerValueArray.item_description.length ;k++ ){
      alert(mtdPerValueArray.sumtotal[k]);
           item_description= (mtdPerValueArray.item_description[k]);
		   sumtotal= (mtdPerValueArray.sumtotal[k]);
			
          
          

          
      }
    }
   
  //console.log(mtdArray);
  
  //console.log(mtdArray.MTDthisyear);
  
  //mtdRnValueThis = [(mtdArray.ValueThisYear/100000).toFixed(2),(mtdArray.ValueMTDthisyear/100000).toFixed(2),(mtdArray.ValueYTDthisyear/100000).toFixed(2)];

  //mtdRnValueLast = [(mtdArray.ValueLastYear/100000).toFixed(2),(mtdArray.ValueLastYearMTD/100000).toFixed(2),(mtdArray.ValueLastYearYTD/100000).toFixed(2)];

  console.log(mtdValueArray);
  /*console.log(mtdRoomNightThis);
  console.log(mtdRoomNightLast);
  console.log(mtdRnValueThis);
  console.log(mtdRnValueLast);*/

  
  // -----------------------
  // - MONTHLY SALES CHART -
  // -----------------------
  // Get context with jQuery - using jQuery's .get() method.

  var saleChartchk = $('#saleChart').val();
  if( typeof saleChartchk != 'undefined'){
	  
    var saleChartCanvas = $('#saleChart').get(0).getContext('2d');
    var saleChart       = new Chart(saleChartCanvas);
	
    var saleChartValueCanvas = $('#saleChartValue').get(0).getContext('2d');
    var saleChartValue       = new Chart(saleChartValueCanvas);
	
    var mtdChartCanvas = $('#mtdChart').get(0).getContext('2d');
    var mtdChartRn       = new Chart(mtdChartCanvas);
    
    var mtdChartValueCanvas = $('#mtdChartValue').get(0).getContext('2d');
    var mtdChartValue       = new Chart(mtdChartValueCanvas);

    var todayChartCanvas = $('#todayChart').get(0).getContext('2d');
    var todayChartRn       = new Chart(todayChartCanvas);

    var ytdChartCanvas = $('#ytdChart').get(0).getContext('2d');
    var ytdChartRn       = new Chart(ytdChartCanvas);
    
    var mtdChartValueCanvas = $('#mtdChartValue').get(0).getContext('2d');
    var mtdChartValue       = new Chart(mtdChartValueCanvas);

    var todayChartValueCanvas = $('#todayChartValue').get(0).getContext('2d');
    var todayChartValue       = new Chart(todayChartValueCanvas);

    var ytdChartValueCanvas = $('#ytdChartValue').get(0).getContext('2d');
    var ytdChartValue       = new Chart(ytdChartValueCanvas);
	
	var mtdPerChartCanvas = $('#mtdPerChart').get(0).getContext('2d');
    var mtdPerChart       = new Chart(mtdPerChartCanvas);
	
	//var mtdPerChartValueCanvas = $('#mtdPerChartValue').get(0).getContext('2d');
    //var mtdPerChartValue       = new Chart(mtdPerChartValueCanvas);
	
	
	
	
	
}

		/*let mtdPerChart = document.getElementById('mtdPerChart').getContext('2d');
        let ytdPerChart = document.getElementById('ytdPerChart').getContext('2d');

        mtdChart = new Chart(mtdPerChart, {
            // The type of chart we want to create
            type: 'bar',

            // The data for our dataset
            data: {
                labels: exeNameArr,
                datasets: [{
                    label: 'Last Year : '+mtdPreValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(153, 102, 255,0.5)',
                    borderColor: 'rgba(153, 102, 255,1)',
                    data: mtdPreValueArr
                },
                {
                    label: 'This Year : '+mtdThisValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
                    borderColor: 'rgba(54, 162, 235,1)',
                    data: mtdThisValueArr
                }]
            },

            // Configuration options go here
            options: {
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                },
                title:{
                    display:true,
                    text:'Total Budget : '+budgetValueArr.reduce(sumofArray)+' '
                }
            }
        });

         ytdChart = new Chart(ytdPerChart, {
            // The type of chart we want to create
            type: 'bar',

            // The data for our dataset
            data: {
                labels: exeNameArr,
                datasets: [{
                    label: 'Last Year : '+ytdPreValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(255, 205, 86,0.5)',
                    borderColor: 'rgb(255, 205, 86,1)',
                    data: ytdPreValueArr
                },
                {
                    label: 'This Year : '+ytdThisValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgb(54, 162, 235,0.5)',
                    borderColor: 'rgb(54, 162, 235,1)',
                    data: ytdThisValueArr
                }]
            },

            // Configuration options go here
            options: {
                plugins: {
                    labels:{
                      render:'value',  
                    }
                    
                },
                title:{
                    text:'Total Budget : '+budgetValueArr.reduce(sumofArray)+'',
                    display:true
                }
            }
        });*/
		
	
	

  var saleChartData = {
    labels  : month,
    datasets: [
      {
        label               : 'Room nights this year',
        fillColor           : '#87CEFA',
        strokeColor         : '#87CEFA',
        pointColor          : '#87CEFA',
        pointStrokeColor    : '#87CEFA',
        pointHighlightFill  : '#87CEFA',
        pointHighlightStroke: '#87CEFA',
        data                : budgetNight
      },
      {
        label               : 'Room nights past year',
        fillColor           : 'rgba(60,141,188,0.8)',
        strokeColor         : 'rgba(60,141,188,0.8)',
        pointColor          : 'rgba(60,141,188,0.8)',
        pointStrokeColor    : 'rgba(60,141,188,0.8)',
        pointHighlightFill  : 'rgba(60,141,188,0.8)',
        pointHighlightStroke: 'rgba(60,141,188,0.8)',
        data                : roomNight
      }
      
    ]
  };

  var saleChartValueData = {
    labels  : month,
    datasets: [
      {
        label               : 'Room nights this year',
        fillColor           : '#DCD0FF',
        strokeColor         : '#DCD0FF',
        pointColor          : '#DCD0FF',
        pointStrokeColor    : '#DCD0FF',
        pointHighlightFill  : '#DCD0FF',
        pointHighlightStroke: '#DCD0FF',
        data                : budgetNightValue
      },
      {
        label               : 'Room nights this year',
        fillColor           : '#8e44ad',
        strokeColor         : '#8e44ad',
        pointColor          : '#8e44ad',
        pointStrokeColor    : '#8e44ad',
        pointHighlightFill  : '#8e44ad',
        pointHighlightStroke: '#8e44ad',
        data                : roomNightValue
      }
      
    ]
  };
 var mtdPerChartData = {
    labels  : mtdPerValueArray.item_description,
    datasets: [
      {
        label               : 'Room nights this year',
        fillColor           : '#87CEFA',
        strokeColor         : '#87CEFA',
        pointColor          : '#87CEFA',
        pointStrokeColor    : '#87CEFA',
        pointHighlightFill  : '#87CEFA',
        pointHighlightStroke: '#87CEFA',
        data                : mtdPerValueArray.sumtotal
      },
      {
        label               : 'Room nights past year',
        fillColor           : 'rgba(60,141,188,0.8)',
        strokeColor         : 'rgba(60,141,188,0.8)',
        pointColor          : 'rgba(60,141,188,0.8)',
        pointStrokeColor    : 'rgba(60,141,188,0.8)',
        pointHighlightFill  : 'rgba(60,141,188,0.8)',
        pointHighlightStroke: 'rgba(60,141,188,0.8)',
        data                : budgetNightValue
	  }
    ]
  };
  
    
 if(mtdArray != null){ 
	 
 console.log(mtdArray.ThisYearRn);  
  var todayChartRnData = {
    labels  : ["Today"],
    datasets: [
      {
        label               : 'Room nights this year',
        fillColor           : '#3ee096',
        strokeColor         : '#3ee096',
        pointColor          : '#3ee096',
        pointStrokeColor    : '#3ee096',
        pointHighlightFill  : '#3ee096',
        pointHighlightStroke: '#3ee096',
        data                : [mtdArray.LastYearRn]
      },
      {
        label               : 'Room nights this year',
        fillColor           : '#00a65a',
        strokeColor         : '#00a65a',
        pointColor          : '#00a65a',
        pointStrokeColor    : '#00a65a',
        pointHighlightFill  : '#00a65a',
        pointHighlightStroke: '#00a65a',
        data                : [mtdArray.ThisYearRn]
      }
      
    ]
  };



  var mtdChartRnData = {
    labels  : ["MTD"],
    datasets: [
      {
        label               : 'Room nights this year',
        fillColor           : '#3ee096',
        strokeColor         : '#3ee096',
        pointColor          : '#3ee096',
        pointStrokeColor    : '#3ee096',
        pointHighlightFill  : '#3ee096',
        pointHighlightStroke: '#3ee096',
        data                : [mtdArray.LastYearMTD]
      },
      {
        label               : 'Room nights this year',
        fillColor           : '#00a65a',
        strokeColor         : '#00a65a',
        pointColor          : '#00a65a',
        pointStrokeColor    : '#00a65a',
        pointHighlightFill  : '#00a65a',
        pointHighlightStroke: '#00a65a',
        data                : [mtdArray.MTDthisyear]
      }
      
    ]
  };

  var ytdChartRnData = {
    labels  : ["YTD"],
    datasets: [
      {
        label               : 'Room nights this year',
        fillColor           : '#3ee096',
        strokeColor         : '#3ee096',
        pointColor          : '#3ee096',
        pointStrokeColor    : '#3ee096',
        pointHighlightFill  : '#3ee096',
        pointHighlightStroke: '#3ee096',
        data                : [mtdArray.LastYearYTD]
      },
      {
        label               : 'Room nights this year',
        fillColor           : '#00a65a',
        strokeColor         : '#00a65a',
        pointColor          : '#00a65a',
        pointStrokeColor    : '#00a65a',
        pointHighlightFill  : '#00a65a',
        pointHighlightStroke: '#00a65a',
        data                : [mtdArray.YTDthisyear]
      }
      
    ]
  };

  var todayChartValueData = {
    labels  :["Today"],
    datasets: [
      {
        label               : 'Room nights this year',
        fillColor           : '#48d0f2',
        strokeColor         : '#48d0f2',
        pointColor          : '#48d0f2',
        pointStrokeColor    : '#48d0f2',
        pointHighlightFill  : '#48d0f2',
        pointHighlightStroke: '#48d0f2',
        data                : [mtdValueArray.ValueLastYear]
      },
      {
        label               : 'Room nights this year',
        fillColor           : '#02aad3',
        strokeColor         : '#02aad3',
        pointColor          : '#02aad3',
        pointStrokeColor    : '#02aad3',
        pointHighlightFill  : '#02aad3',
        pointHighlightStroke: '#02aad3',
        data                : [mtdValueArray.ValueThisYear]
      }
    ]
  };

  var mtdChartValueData = {
    labels  :["MTD"],
    datasets: [
      {
        label               : 'Room nights this year',
        fillColor           : '#48d0f2',
        strokeColor         : '#48d0f2',
        pointColor          : '#48d0f2',
        pointStrokeColor    : '#48d0f2',
        pointHighlightFill  : '#48d0f2',
        pointHighlightStroke: '#48d0f2',
        data                : [mtdValueArray.ValueLastYearMTD]
      },
      {
        label               : 'Room nights this year',
        fillColor           : '#02aad3',
        strokeColor         : '#02aad3',
        pointColor          : '#02aad3',
        pointStrokeColor    : '#02aad3',
        pointHighlightFill  : '#02aad3',
        pointHighlightStroke: '#02aad3',
        data                : [mtdValueArray.ValueMTDthisyear]
      }
    ]
  };   

  var ytdChartValueData = {
    labels  :["YTD"],
    datasets: [
      {
        label               : 'Room nights this year',
        fillColor           : '#48d0f2',
        strokeColor         : '#48d0f2',
        pointColor          : '#48d0f2',
        pointStrokeColor    : '#48d0f2',
        pointHighlightFill  : '#48d0f2',
        pointHighlightStroke: '#48d0f2',
        data                : [mtdValueArray.ValueLastYearYTD]
      },
      {
        label               : 'Room nights this year',
        fillColor           : '#02aad3',
        strokeColor         : '#02aad3',
        pointColor          : '#02aad3',
        pointStrokeColor    : '#02aad3',
        pointHighlightFill  : '#02aad3',
        pointHighlightStroke: '#02aad3',
        data                : [mtdValueArray.ValueYTDthisyear]
      }
    ]
  };      
 }    
  var saleChartOptions = {
    // Boolean - If we should show the scale at all
    showScale               : true,
    // Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : true,
    // String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    // Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    // Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    // Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    // Boolean - Whether the line is curved between points
    bezierCurve             : true,
    // Number - Tension of the bezier curve between points
    bezierCurveTension      : 0.3,
    // Boolean - Whether to show a dot for each point
    pointDot                : true,
    // Number - Radius of each point dot in pixels
    pointDotRadius          : 4,
    // Number - Pixel width of point dot stroke
    pointDotStrokeWidth     : 1,
    // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,
    // Boolean - Whether to show a stroke for datasets
    datasetStroke           : true,
    // Number - Pixel width of dataset stroke
    datasetStrokeWidth      : 2,
    // Boolean - Whether to fill the dataset with a color
    datasetFill             : false,
    // String - A legend template
    legendTemplate          : '<ul class=\'<%=label.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio     : true,
    // Boolean - whether to make the chart responsive to window resizing
    responsive              : true
  };

  // Create the line chart
  if( typeof saleChartchk != 'undefined'){
    saleChart.Line(saleChartData, saleChartOptions);
    saleChartValue.Line(saleChartValueData, saleChartOptions);
    todayChartRn.Bar(todayChartRnData, saleChartOptions);
    mtdChartRn.Bar(mtdChartRnData, saleChartOptions);
    ytdChartRn.Bar(ytdChartRnData, saleChartOptions);
    todayChartValue.Bar(todayChartValueData, saleChartOptions);
    mtdChartValue.Bar(mtdChartValueData, saleChartOptions);
    ytdChartValue.Bar(ytdChartValueData, saleChartOptions);
	mtdPerChart.Bar(mtdPerChartData, saleChartOptions);
	
  }
  // ---------------------------
  // - END MONTHLY SALES CHART -
  // ---------------------------
});
</script>