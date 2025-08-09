import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head } from "@inertiajs/react";

const TrucksProfile = () => {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: "Truck Profile",
            href: "/trucks",
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={""} />
        </AppLayout>
    );
};

export default TrucksProfile;
