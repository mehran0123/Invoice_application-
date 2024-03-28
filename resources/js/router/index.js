import {createRouter ,createWebHistory} from "vue-router";

import InvoiceIndex from '../components/invoices/index.vue'
import CreateInvoice from '../components/invoices/new.vue'
import NOtFound from '../components/NotFound.vue'
import ShowInvoice from '../components/invoices/show.vue'
import EditInvoice from '../components/invoices/edit.vue'

const routes = [
    {
        path: '/',
        component: InvoiceIndex
    },
    {
    path: '/invoice/new',
        component: CreateInvoice
    },
    {
        path: '/invoice/show/:id',
        component: ShowInvoice,
        props:true
    },
    {
        path: '/invoice/edit/:id',
        component: EditInvoice,
        props:true
    },

    {
        path: '/:pathMath(.*)*',
        component: NOtFound
    },

]

const router = createRouter({
    history:createWebHistory(),
    routes
})


export default router
