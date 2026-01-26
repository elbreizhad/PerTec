import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind';
import sitemap from '@astrojs/sitemap';

export default defineConfig({
  site: 'https://pertec.fr',
  output: 'static',
  integrations: [tailwind(), sitemap()],
  compressHTML: true,
  build: {
    assets: 'assets'
  }
});
