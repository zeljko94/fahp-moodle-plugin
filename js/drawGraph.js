
function drawGraph(data){
	FusionCharts.ready(function(){
        var fusioncharts = new FusionCharts({
                type: 'column2d',
                renderAt: 'chart-1',
                width: '800',
                height: '600',
                dataFormat: 'json',
                dataSource: {
                    "chart": {
                        "theme": "fint",
                        "caption": "Težinska vrijednost kriterija",
                        "baseFontSize": "20",
                        "outCnvBaseFontSize": "20",
                        "subcaption": "",
                        "xaxisname": "Kriteriji",
                        "yaxisname": "Težinska vrijednost",
                        "numberPrefix": "",
                        "numberSuffix": "%",
                        "rotateValues": "0",
                        "placeValuesInside": "0",
                        "valueFontColor": "#000000",
                        "valueBgColor": "#FFFFFF",
                        "valueBgAlpha": "50",
                        //Disabling number scale compression
                        "formatNumberScale": "0",
                        //Defining custom decimal separator
                        "decimalSeparator": ",",
                        //Defining custom thousand separator
                        "thousandSeparator": "."
                    },
                    "data": data
                }
            }
        );
        fusioncharts.render();
    });
}


document.addEventListener('DOMContentLoaded', function(){
	var data = JSON.parse(document.getElementById("graphData").innerHTML);
	drawGraph(data);
}, false);