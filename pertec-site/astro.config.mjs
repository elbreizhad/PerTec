import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind';

export default defineConfig({
  site: 'https://pertec.fr',
  integrations: [tailwind()],
  compressHTML: true,
});
