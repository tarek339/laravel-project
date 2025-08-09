import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head } from "@inertiajs/react";

const DriversTable = () => {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: "Drivers",
            href: "/drivers",
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Drivers Listing" />
        </AppLayout>
    );
};

export default DriversTable;
