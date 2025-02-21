import { createRouter, createWebHistory } from "vue-router";
import { routes } from "@/router/routes";
import { useRouter } from "vue-router";

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const title = "Issue Report";

router.beforeEach((to, from, next) => {
  const router = useRouter();

  const nearestWithTitle = to.matched
    .slice()
    .reverse()
    .find((r) => r.meta && r.meta.title);

  if (nearestWithTitle) {
    document.title = nearestWithTitle.meta.title + " | " + title || title;
  }

  const isAuthRequired = to.meta.authRequired;

  if (!isAuthRequired) {
    return next();
  }
  else {
    const token = localStorage.getItem('jwtToken');

    if (isAuthRequired && token) {
      next();
    } else {
      router.push("/signin");
    }
  } 
});

export default router;
