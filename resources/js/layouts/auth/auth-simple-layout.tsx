import { type PropsWithChildren } from "react";

interface AuthLayoutProps {
    name?: string;
    title?: string;
    description?: string;
}

export default function AuthSimpleLayout({ children, title, description }: PropsWithChildren<AuthLayoutProps>) {
    return (
        <div className="grid min-h-svh lg:grid-cols-2">
            <div className="flex flex-col gap-4 p-6 md:p-10">
                <div className="flex flex-1 items-center justify-center">
                    <div className="flex w-full max-w-xs flex-col gap-6">
                        <div className="flex flex-col items-center gap-2 text-center">
                            <h1 className="text-2xl font-bold">{title}</h1>
                            <p className="text-sm text-balance text-muted-foreground">{description}</p>
                        </div>
                        {children}
                    </div>
                </div>
            </div>
            <div className="relative hidden bg-muted lg:block">
                <img
                    src="/placeholder.svg"
                    alt="Image"
                    className="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
                />
            </div>
        </div>
    );
}
