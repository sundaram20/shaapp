Resource - Rooms
data.push({id: "g-1", title: "Gopi"})
data.push({parentId: "g-1", id:'gs-1', title: "101"})
data.push({parentId: "g-1", id:'gs-1', title: "101"})

Events - available room
data.push({resourceId: "g-1", start: "2021-01-18", end: "2021-01-18", title: "Availble 21"})
data.push({resourceId: "g-1", start: "2021-01-18", end: "2021-01-18", title: "Occupied 10"})

data.push({resourceId: "g-1", start: "2021-01-19", end: "2021-01-19", title: "Availble 2"})
data.push({resourceId: "gs-1", start: "2021-01-18", end: "2021-01-18", title: "Guest Gopi"})
data.push({resourceId: "gs-1", start: "2021-01-19", end: "2021-01-19", title: "Guest Jeeva"})
data[8] = {resourceId: "138", start: "2021-01-21", end: "2021-01-24", title: "test demo", backgroundColor:'red'}
{resourceId: "g-1", start: "2021-01-18", end: "2021-01-18", avaialble:34, occupied=343 }


var data = [];

for(var i=0; i< result.lenght; i++){
data.push({resourceId:result[i].id, start:result[i].start, end:result[i].end, title:'Avaialble'+result[i].avaialble});
data.push({resourceId:result[i].id, start:result[i].start, end:result[i].end, title:'Occupied'+result[i].occupied});
}