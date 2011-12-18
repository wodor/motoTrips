{{item.id}}
<!--COLUMNSEPARATOR-->
<a href="{{ path('trip_show', { 'id': item.id }) }}">{{item.description}}</a> 
<!--COLUMNSEPARATOR-->
{{item.startDate.format("Y-m-d")}} 
