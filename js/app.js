var app = new Vue({
	el: "#app",
	data: {
		errorMessage: "",
		users: [],
		newUser: { username: "", email: "" },
		clickedUser: { id: "", username: "", email: "" }
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
			 })
			 .catch(error => {
			 	console.log(error);
			 });
		},

		saveUser() {
			let formData = this.appendForm(this.newUser);
			let vm = this;
			axios.post('http://localhost/vuejs-estudos/vue-php-crud/php/api.php?action=create', formData)
			.then((res) => {
				if(res.data.error)
					vm.errorMessage = res.data.message;
				else
					vm.getAllUser();
			})
			.catch(error => {
				console.log(error);
			});
		},

		updateUser() {
			let vm = this;
			let formData = this.appendForm(this.clickedUser);
			axios.post('http://localhost/vuejs-estudos/vue-php-crud/php/api.php?action=update', formData)
			.then(res => {
				if(res.data.error)
					vm.errorMessage = res.data.message;
				else {
					vm.successMessage = res.data.message;
					vm.getAllUser();
				}
			})
			.catch(e => {
				console.log(e)
			});
		},

		selectUser(user) {
			this.clickedUser = user;
		},

		appendForm(data) {
		  const form = new FormData()
		  for ( const key in data ) {
		    form.append(key, data[key]);
		  }
		  return form
		}
	}
});