import router from '@/router';

function addRoutes(routes = []) {
	let idx = 0;
	const len = routes.length;

	while (idx < len) {
		router.addRoute(routes[idx]);

		idx++;
	}
}

export {
	addRoutes
};
