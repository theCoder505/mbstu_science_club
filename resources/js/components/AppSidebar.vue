<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Atom, Award, GalleryVerticalIcon, Glasses, LayoutGrid, QuoteIcon, Settings, Trophy, User } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Applications',
        href: '/applications',
        icon: Award,
    },
    {
        title: 'Advior & Moderators',
        href: '/advisors-and-moderators',
        icon: Glasses,
    },
    {
        title: 'Team',
        href: '/manage-team',
        icon: User,
    },
    {
        title: 'Website Settings',
        href: '/website-settings',
        icon: Settings,
    },
    {
        title: 'Manage Committee',
        href: '/our-committee',
        icon: Trophy,
    },
    {
        title: 'FAQs',
        href: '/faqs',
        icon: QuoteIcon,
    },
    {
        title: 'Gallery',
        href: '/gallery',
        icon: GalleryVerticalIcon,
    },
    {
        title: 'Templates',
        href: '/templates',
        icon: Atom,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
