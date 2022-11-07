<template>

    <draggable
        id="nav-tabs"
        v-model="tabs">
        <el-tag
            v-for="(item, index) in tabs"
            @click="tabClick(item)"
            @close="tabRemove(item)"
            :key="index"
            effect="plain"
            size="medium"
            :class="[
                'nav-tabs',
                (focusTab === item.name) ? 'active' : '',
                'el-tabs__item',
            ]"
            :closable="tabs.length !== 1">
            <i :class="item.icon">&nbsp;&nbsp;</i>{{item.title}}
        </el-tag>
    </draggable>
</template>

<script>

import navTab from "../mixins/navTab";
import draggable from "vuedraggable";

export default {

    name: "vpNavTabs",

    mixins: [navTab],

    components: {draggable},
}
</script>

<style scoped lang="less">

    #nav-tabs {

        text-align: left;
        padding: 0;
        background: #f5f7fa;
        border-bottom: 1px solid #e4e7ed;

        .nav-tabs {

            background: transparent;
            border: none;
            border-radius: 0;
            text-align: center;
            border-left: 1px solid transparent;
            border-right: 1px solid transparent;
            color: var(--title-color);
            font-size: .8rem;
            font-weight: 700;

            &.active {
                background: #fff;
                border-left: 1px solid #e4e7ed;
                border-right: 1px solid #e4e7ed;

                &:after {
                    background: #fff;
                    width: 100%;
                    height: 5px;
                    display: block;
                    content: '';
                    position: absolute;
                    left: 0;
                    bottom: -2px;
                }
            }

            :deep(.el-tag__close) {
                &:hover {
                    background: var(--title-color)!important;
                }
            }
        }
    }
</style>
