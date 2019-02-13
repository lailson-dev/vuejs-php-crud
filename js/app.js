var app = new Vue({
	el: "#app",
	data: {
		erroMessage: "",
		users: []
	},

	created() {
		this.getAllUser();
	},

	methods: {
		getAllUser() {
			let vm = this;
			axios.get('http://localhost/vuejs-estudos/vue-php-crud/php/api.php?action=read')
				 .then((res) => {				 	
				 	if(res.data.error)
				 		vm.errorMessage = res.data.message;
				 	else
				 		vm.users = res.data.users;
				 });
		}
	}
});