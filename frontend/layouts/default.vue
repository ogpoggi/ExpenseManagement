<template>
    <div class="bg-white dark:bg-gray-800">
<!--        <div class="bg-white">-->
<!--            <main class="shadow">-->
<!--                <div class="max-w-screen-lg mx-auto flex flex-row justify-between items-center p-4 sm:p-7 relative z-10 space-x-2 sm:space-x-3">-->
<!--                    <NuxtLink to="/" class="block">-->
<!--                        <h1 class="text-2xl font-black"><span class="text-purple-700">Bill</span>Y-->
<!--                        </h1>-->
<!--                    </NuxtLink>-->

<!--                    <div class="flex items-center text-sm font-semibold">-->
<!--                        <template v-if="$auth.user">-->
<!--                            <a href="" class="text-gray-700 hover:text-purple-700" @click.prevent="logout">-->
<!--                                Log Out-->
<!--                            </a>-->
<!--                            <NuxtLink to="/profile" class="text-gray-700 ml-7 border border-gray-300 hover:border-purple-400 hover:text-purple-700 rounded px-4 py-2">-->
<!--                                Profile-->
<!--                            </NuxtLink>-->
<!--                        </template>-->
<!--                        <template v-else>-->
<!--                            <NuxtLink to="/login" class="text-gray-700 hover:text-purple-700">-->
<!--                                Sign In-->
<!--                            </NuxtLink>-->
<!--                            <NuxtLink to="/register" class="text-gray-700 ml-7 border border-gray-300 hover:border-purple-400 hover:text-purple-700 rounded px-4 py-2">-->
<!--                                Create Account-->
<!--                            </NuxtLink>-->
<!--                        </template>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </main>-->
<!--        </div>-->
        <Navbar/>
      <!--<div
        id="app"
        class="bg-white dark:bg-black"
        :class="dark ? 'dark' : 'light'"
      ></div>-->
        <div class="mx-auto p-4 sm:p-7 mt-10 bg-white dark:bg-gray-800">
            <Nuxt/>
        </div>
      <Footer/>
    </div>
</template>
<script>

import Navbar from "../components/base/Navbar";
import Footer from "../components/base/Footer";
import { mapGetters } from 'vuex';
export default {
  beforeMount() {
    document.getElementsByTagName('html')[0].classList.add(localStorage.theme)
  },
  computed: {
    ...mapGetters(['dark'])
  },
  components: {Footer, Navbar},

}

</script>

<style>
.cover-gradient {
  background: linear-gradient(
    169.4deg,
    rgba(57, 132, 244, 0.04) -6.01%,
    rgba(12, 211, 255, 0.04) 36.87%,
    rgba(47, 124, 240, 0.04) 78.04%,
    rgba(14, 101, 232, 0.04) 103.77%
  );
}
.cover-gradient-2 {
  background: linear-gradient(
    169.4deg,
    rgba(57, 132, 244, 0.1) -6.01%,
    rgba(12, 211, 255, 0.1) 36.87%,
    rgba(47, 124, 240, 0.1) 78.04%,
    rgba(14, 101, 232, 0.1) 103.77%
  );
}
.bg-blue-gradient,
.text-gradient {
  background: linear-gradient(136.91deg, #468ef9 -12.5%, #0c66ee 107.5%);
}
.text-gradient {
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.slide-enter-active {
  -moz-transition-duration: 0.3s;
  -webkit-transition-duration: 0.3s;
  -o-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -moz-transition-timing-function: ease-in;
  -webkit-transition-timing-function: ease-in;
  -o-transition-timing-function: ease-in;
  transition-timing-function: ease-in;
}

.slide-leave-active {
  -moz-transition-duration: 0.3s;
  -webkit-transition-duration: 0.3s;
  -o-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -moz-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
  -webkit-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
  -o-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
  transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
}

.slide-enter-to,
.slide-leave {
  max-height: 100px;
  overflow: hidden;
}

.slide-enter,
.slide-leave-to {
  overflow: hidden;
  max-height: 0;
}
</style>
