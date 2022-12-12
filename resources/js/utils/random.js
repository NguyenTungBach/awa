export function getRandomItemInList(list = []) {
	return list[Math.floor(Math.random() * list.length)];
}

export function randomIntFromInterval(min, max) {
	return Math.floor(Math.random() * (max - min + 1) + min);
}
