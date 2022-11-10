
const searchItem = document.querySelector('#searchInput');
const searchBtn = document.querySelector('button#searchBtn');
const rooms = document.querySelectorAll('.room');
const mainSection = document.querySelector('.main-section');

//const dateIn = document.getElementsByName('date_in').value;
//const dateOut = document.getElementsByName('date_out');

//console.log(dateIn, dateOut)

///const td1 = document.getElementsByTagName('td')[4].textContent
//const td2 = document.getElementsByTagName('td')[5].textContent



//console.log(td1,td2)

const datein = document.getElementById('dateInInput');
datein.addEventListener('input',function(){
	const dateinValue = datein.value;
	console.log(dateinValue)
})

const dateout = document.getElementById('dateOutInput');
dateout.addEventListener('input', function(){
	const dateoutValue = dateout.value;
	console.log(dateoutValue)
})



//const dateInValue = dateIn.value;
//const dateoutValue = dateOut.value;

//const checkBtn = document.querySelector('.btn book-button')

//function dates(){
//	console.log(dateIn.value)
//	console.log(dateOut.value);
//}

//checkBtn.addEventListener('click', dates)



function search() {
	console.log('hello', rooms);
	if (!searchItem.value) return;
	mainSection.innerHTML = '';
	let match = false;
	rooms.forEach(room=>{
		let title = room.querySelector('.card-title').innerText.toLowerCase();
		if(title.includes(searchItem.value.toLowerCase())){
			mainSection.appendChild(room)
			match = true;
		}
	});	
	if (!match) {
		mainSection.innerHTML = `
		<div class="d-flex mx-auto">
			<h3 class="text-danger text-center">No '${searchItem.value}' Found</h3>
		</div>`;	
	}
}

searchBtn.addEventListener('click', search)

document.addEventListener('keyup', (e)=>{
	if(e.keyCode !== 13) return;
	let isFocused = (document.activeElement === searchItem) 
	if(isFocused){
		this.search()
	}
});

