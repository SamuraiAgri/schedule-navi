<script setup>
/**
 * 回答一覧テーブルコンポーネント
 * 全回答者の出欠状況を表形式で表示
 */
import { computed } from 'vue';
import StatusIcon from '@/Components/StatusIcon.vue';

const props = defineProps({
    dates: {
        type: Array,
        required: true,
    },
    responses: {
        type: Array,
        required: true,
    },
    statistics: {
        type: Object,
        required: true,
    },
});

// 最も参加者が多い日程ID
const bestDateId = computed(() => props.statistics.summary.bestDateId);
</script>

<template>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="sticky left-0 z-10 bg-gray-50 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]"
                        >
                            参加者
                        </th>
                        <th
                            v-for="date in dates"
                            :key="date.id"
                            :class="[
                                'px-3 py-3 text-center text-xs font-medium uppercase tracking-wider min-w-[80px]',
                                date.id === bestDateId ? 'bg-green-50 text-green-700' : 'text-gray-500',
                            ]"
                        >
                            <div class="font-semibold text-sm normal-case">
                                {{ date.formattedDate }}
                            </div>
                            <div v-if="date.formattedTime" class="text-xs font-normal normal-case text-gray-400">
                                {{ date.formattedTime }}
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[150px]">
                            コメント
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- 回答者行 -->
                    <tr v-for="response in responses" :key="response.id" class="hover:bg-gray-50">
                        <td class="sticky left-0 z-10 bg-white px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ response.participantName }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ response.createdAt }}
                            </div>
                        </td>
                        <td
                            v-for="date in dates"
                            :key="date.id"
                            :class="[
                                'px-3 py-3 text-center',
                                date.id === bestDateId ? 'bg-green-50' : '',
                            ]"
                        >
                            <StatusIcon :status="response.details[date.id]" />
                        </td>
                        <td class="px-4 py-3">
                            <div v-if="response.comment" class="text-sm text-gray-600 max-w-xs truncate" :title="response.comment">
                                {{ response.comment }}
                            </div>
                            <span v-else class="text-gray-300">-</span>
                        </td>
                    </tr>

                    <!-- 集計行 -->
                    <tr class="bg-gray-50 font-medium">
                        <td class="sticky left-0 z-10 bg-gray-50 px-4 py-3 text-sm text-gray-700">
                            集計
                        </td>
                        <td
                            v-for="date in dates"
                            :key="date.id"
                            :class="[
                                'px-3 py-3 text-center text-sm',
                                date.id === bestDateId ? 'bg-green-100' : '',
                            ]"
                        >
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-green-600">○{{ statistics.dateStats[date.id]?.ok || 0 }}</span>
                                <span class="text-yellow-600">△{{ statistics.dateStats[date.id]?.maybe || 0 }}</span>
                                <span class="text-red-600">×{{ statistics.dateStats[date.id]?.ng || 0 }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
