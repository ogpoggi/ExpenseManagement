<template>
  <div class="w-1/2 mx-auto bg-white p-5 rounded-lg">
    <Errors class="mb-5" :errors="errors"></Errors>

    <form autoComplete="off" @submit.prevent="submitForm">
      <div class="mt-4">
        <Label html-for="name">Name</Label>
          <Input
            id="name"
            v-model="name"
            type="text"
            name="name"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            placeholder="name"
            required
            auto-focus
            auto-complete="off"
          />
      </div>
      <div>
        <Label html-for="email">Email</Label>

        <Input
          id="email"
          v-model="email"
          type="email"
          class="block mt-1 w-full"
          placeholder="email"
          required
          auto-focus
          auto-complete="off"
        />
      </div>

      <div class="mt-4">
        <Label html-for="password">Password</Label>

        <Input
          id="password"
          v-model="password"
          type="password"
          class="block mt-1 w-full"
          placeholder="password"
          required
          auto-focus
          auto-complete="off"
        />
      </div>
      <div class="mt-4">
        <Label html-for="password_confirmation">Confirm Password</Label>
        <Input
          id="password_confirmation"
          v-model="password_confirmation"
          type="password"
          class="block mt-1 w-full"
          placeholder="password"
          required
          auto-focus
          auto-complete="off"
        />
      </div>

      <div class="flex items-center justify-end mt-4">
        <NuxtLink to="/login" class="underline text-sm text-gray-600 hover:text-gray-900">
          Already have an account ?
        </NuxtLink>

        <Button class="ml-3">Register</Button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  middleware: 'auth',
  auth: 'guest',

  data: () => ({
    errors: [],
    name: '',
    email: '',
    password: '',
    password_confirmation: '',

  }),

  head() {
    return {
      title: 'Sign Up',
    }
  },

  methods: {
    async submitForm(event) {
      this.errors = [];
      const response = await this.$axios.$post('/register', {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation,
      }).then((response) => {
        this.$auth.loginWith('cookie', {
          data: {
            email: this.email,
            password: this.password,
          },
        }).then(() => this.$router.push('/'))
          .catch(error => {
            if (error.response.status !== 422) throw error

            this.errors = Object.values(error.response.data.errors).flat();
          })
      }).catch(error => {
        if (error.response.status !== 422) throw error
        this.errors = Object.values(error.response.data.errors).flat();
      })

    }
  }
}
</script>
