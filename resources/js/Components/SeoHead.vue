<script setup>
/**
 * SEOメタタグコンポーネント
 * 各ページで動的にOGP・Twitterカード等を設定
 */
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    description: {
        type: String,
        default: '30秒で作れるシンプルな日程調整サービス。飲み会から会議まで、会員登録不要で無料でご利用いただけます。',
    },
    ogImage: {
        type: String,
        default: '/og-image.png',
    },
    ogType: {
        type: String,
        default: 'website',
    },
    canonical: {
        type: String,
        default: '',
    },
    noindex: {
        type: Boolean,
        default: false,
    },
});

const pageTitle = computed(() => {
    return props.title ? `${props.title} - Schedule-Navi` : 'Schedule-Navi - シンプルな日程調整サービス';
});

const currentUrl = computed(() => {
    return props.canonical || (typeof window !== 'undefined' ? window.location.href : '');
});
</script>

<template>
    <Head>
        <title>{{ pageTitle }}</title>
        <meta name="description" :content="description" />
        <meta v-if="noindex" name="robots" content="noindex, nofollow" />
        
        <!-- OGP -->
        <meta property="og:title" :content="pageTitle" />
        <meta property="og:description" :content="description" />
        <meta property="og:type" :content="ogType" />
        <meta property="og:url" :content="currentUrl" />
        <meta property="og:image" :content="ogImage" />
        
        <!-- Twitter Cards -->
        <meta name="twitter:title" :content="pageTitle" />
        <meta name="twitter:description" :content="description" />
        <meta name="twitter:image" :content="ogImage" />
        
        <!-- Canonical -->
        <link v-if="canonical" rel="canonical" :href="canonical" />
    </Head>
</template>
