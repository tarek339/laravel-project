import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head } from "@inertiajs/react";

const CompaniesTable = () => {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: "Companies",
            href: "/companies",
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Companies Listing" />
        </AppLayout>
    );
};

export default CompaniesTable;
