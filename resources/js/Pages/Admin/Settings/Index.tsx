import { ChangeEvent, FormEvent, useEffect, useMemo, useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Head, router, useForm } from '@inertiajs/react';
import { Database, Download, Info, Trash2, X } from 'lucide-react';
import { PageProps } from '@/types';

type SettingsPayload = {
    site: {
        name: string;
        email: string;
        support_email?: string | null;
        url: string;
        timezone: string;
        currency: string;
    };
    branding: {
        logo_path?: string | null;
        logo_url?: string | null;
    };
    security: {
        max_login_attempts: number | null;
        lockout_time: number | null;
        session_timeout: number | null;
        two_factor_threshold: number | null;
        admin_approval_threshold: number | null;
    };
};

type Props = PageProps & { settings: SettingsPayload };

export default function Index({ settings }: Props) {
    const initialValues = useMemo(
        () => ({
            site_name: settings.site.name ?? '',
            site_email: settings.site.email ?? '',
            support_email: settings.site.support_email ?? '',
            app_url: settings.site.url ?? '',
            timezone: settings.site.timezone ?? '',
            currency: settings.site.currency ?? '',
            site_logo: null as File | null,
            remove_logo: false,
            security_max_login_attempts: Number(settings.security.max_login_attempts ?? 5),
            security_lockout_time: Number(settings.security.lockout_time ?? 30),
            security_session_timeout: Number(settings.security.session_timeout ?? 30),
            security_two_factor_threshold: Number(settings.security.two_factor_threshold ?? 100000),
            security_admin_approval_threshold: Number(settings.security.admin_approval_threshold ?? 1000000),
        }),
        [settings]
    );

    const form = useForm(initialValues);
    const { data, setData, errors, processing } = form;

    const [logoPreview, setLogoPreview] = useState<string | null>(settings.branding.logo_url ?? null);
    const [logoObjectUrl, setLogoObjectUrl] = useState<string | null>(null);

    useEffect(() => {
        return () => {
            if (logoObjectUrl) {
                URL.revokeObjectURL(logoObjectUrl);
            }
        };
    }, [logoObjectUrl]);

    const handleClearCache = () => {
        if (confirm('Clear application cache?')) {
            router.post('/admin/settings/clear-cache');
        }
    };

    const handleRunMigrations = () => {
        if (confirm('Run database migrations? This may affect the database.')) {
            router.post('/admin/settings/run-migrations');
        }
    };

    const handleLogoChange = (event: ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0] ?? null;

        if (logoObjectUrl) {
            URL.revokeObjectURL(logoObjectUrl);
            setLogoObjectUrl(null);
        }

        setData('site_logo', file);
        setData('remove_logo', false);

        if (file) {
            const url = URL.createObjectURL(file);
            setLogoPreview(url);
            setLogoObjectUrl(url);
        } else {
            setLogoPreview(settings.branding.logo_url ?? null);
        }
    };

    const handleRemoveLogo = () => {
        if (logoObjectUrl) {
            URL.revokeObjectURL(logoObjectUrl);
            setLogoObjectUrl(null);
        }

        setData('site_logo', null);
        setData('remove_logo', true);
        setLogoPreview(null);
    };

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        form.put('/admin/settings', {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                setData('site_logo', null);
                setData('remove_logo', false);
                if (!data.site_logo) {
                    setLogoPreview(settings.branding.logo_url ?? null);
                }
            },
        });
    };

    return (
        <AdminLayout>
            <Head title="Settings" />

            <div className="space-y-6">
                <div>
                    <h1 className="text-3xl font-bold text-slate-50">Settings</h1>
                    <p className="text-slate-400 mt-1">Manage application configuration, branding, and security.</p>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Site Information</CardTitle>
                            <CardDescription className="text-slate-400">
                                Update the public-facing details for your banking portal.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label htmlFor="site_name" className="block text-sm font-medium text-slate-300">
                                        Site Name
                                    </label>
                                    <input
                                        id="site_name"
                                        type="text"
                                        value={data.site_name}
                                        onChange={(e) => setData('site_name', e.target.value)}
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 placeholder-slate-500 focus:border-emerald-500 focus:ring-emerald-500"
                                        placeholder="Banko"
                                        required
                                    />
                                    {errors.site_name && <p className="mt-2 text-sm text-red-400">{errors.site_name}</p>}
                                </div>
                                <div>
                                    <label htmlFor="site_email" className="block text-sm font-medium text-slate-300">
                                        Primary Email
                                    </label>
                                    <input
                                        id="site_email"
                                        type="email"
                                        value={data.site_email}
                                        onChange={(e) => setData('site_email', e.target.value)}
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 placeholder-slate-500 focus:border-emerald-500 focus:ring-emerald-500"
                                        placeholder="support@banko.test"
                                        required
                                    />
                                    {errors.site_email && <p className="mt-2 text-sm text-red-400">{errors.site_email}</p>}
                                </div>
                                <div>
                                    <label htmlFor="support_email" className="block text-sm font-medium text-slate-300">
                                        Support Email
                                    </label>
                                    <input
                                        id="support_email"
                                        type="email"
                                        value={data.support_email ?? ''}
                                        onChange={(e) => setData('support_email', e.target.value)}
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 placeholder-slate-500 focus:border-emerald-500 focus:ring-emerald-500"
                                        placeholder="help@banko.test"
                                    />
                                    {errors.support_email && (
                                        <p className="mt-2 text-sm text-red-400">{errors.support_email}</p>
                                    )}
                                </div>
                                <div>
                                    <label htmlFor="app_url" className="block text-sm font-medium text-slate-300">
                                        Application URL
                                    </label>
                                    <input
                                        id="app_url"
                                        type="url"
                                        value={data.app_url}
                                        onChange={(e) => setData('app_url', e.target.value)}
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 placeholder-slate-500 focus:border-emerald-500 focus:ring-emerald-500"
                                        placeholder="https://banko.test"
                                        required
                                    />
                                    {errors.app_url && <p className="mt-2 text-sm text-red-400">{errors.app_url}</p>}
                                </div>
                                <div>
                                    <label htmlFor="timezone" className="block text-sm font-medium text-slate-300">
                                        Timezone
                                    </label>
                                    <input
                                        id="timezone"
                                        type="text"
                                        value={data.timezone}
                                        onChange={(e) => setData('timezone', e.target.value)}
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 placeholder-slate-500 focus:border-emerald-500 focus:ring-emerald-500"
                                        placeholder="UTC"
                                        required
                                    />
                                    {errors.timezone && <p className="mt-2 text-sm text-red-400">{errors.timezone}</p>}
                                </div>
                                <div>
                                    <label htmlFor="currency" className="block text-sm font-medium text-slate-300">
                                        Currency
                                    </label>
                                    <input
                                        id="currency"
                                        type="text"
                                        value={data.currency}
                                        onChange={(e) => setData('currency', e.target.value.toUpperCase())}
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 placeholder-slate-500 focus:border-emerald-500 focus:ring-emerald-500 uppercase"
                                        placeholder="NGN"
                                        required
                                    />
                                    {errors.currency && <p className="mt-2 text-sm text-red-400">{errors.currency}</p>}
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Branding</CardTitle>
                            <CardDescription className="text-slate-400">
                                Upload or replace the logo displayed across the platform.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div className="flex flex-col md:flex-row md:items-center md:space-x-6 space-y-4 md:space-y-0">
                                <div className="h-24 w-24 rounded-lg border border-slate-700 bg-slate-950 flex items-center justify-center overflow-hidden">
                                    {logoPreview ? (
                                        <img src={logoPreview} alt="Site logo preview" className="h-full w-full object-cover" />
                                    ) : (
                                        <span className="text-xs text-slate-500 text-center px-2">No logo uploaded</span>
                                    )}
                                </div>
                                <div className="flex-1 space-y-3">
                                    <div>
                                        <label htmlFor="site_logo" className="block text-sm font-medium text-slate-300">
                                            Upload Logo
                                        </label>
                                        <input
                                            id="site_logo"
                                            type="file"
                                            accept="image/*"
                                            onChange={handleLogoChange}
                                            className="mt-2 block w-full text-sm text-slate-300 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-900 hover:file:bg-emerald-400"
                                        />
                                        {errors.site_logo && <p className="mt-2 text-sm text-red-400">{errors.site_logo}</p>}
                                        <p className="mt-2 text-xs text-slate-500">Accepted formats: PNG, JPG, SVG. Max size 2MB.</p>
                                    </div>
                                    {(logoPreview || settings.branding.logo_url) && (
                                        <Button
                                            type="button"
                                            variant="outline"
                                            onClick={handleRemoveLogo}
                                            className="bg-slate-950 border-slate-700 text-slate-200 hover:bg-slate-800 hover:text-slate-50"
                                        >
                                            <X className="h-4 w-4 mr-2" />
                                            Remove Logo
                                        </Button>
                                    )}
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Security Controls</CardTitle>
                            <CardDescription className="text-slate-400">
                                Configure access policies and transaction safeguards.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label htmlFor="security_max_login_attempts" className="block text-sm font-medium text-slate-300">
                                        Max Login Attempts
                                    </label>
                                    <input
                                        id="security_max_login_attempts"
                                        type="number"
                                        min={1}
                                        max={20}
                                        value={data.security_max_login_attempts}
                                        onChange={(e) => setData('security_max_login_attempts', Number(e.target.value))}
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 focus:border-emerald-500 focus:ring-emerald-500"
                                        required
                                    />
                                    {errors.security_max_login_attempts && (
                                        <p className="mt-2 text-sm text-red-400">{errors.security_max_login_attempts}</p>
                                    )}
                                </div>
                                <div>
                                    <label htmlFor="security_lockout_time" className="block text-sm font-medium text-slate-300">
                                        Lockout Time (minutes)
                                    </label>
                                    <input
                                        id="security_lockout_time"
                                        type="number"
                                        min={1}
                                        max={1440}
                                        value={data.security_lockout_time}
                                        onChange={(e) => setData('security_lockout_time', Number(e.target.value))}
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 focus:border-emerald-500 focus:ring-emerald-500"
                                        required
                                    />
                                    {errors.security_lockout_time && (
                                        <p className="mt-2 text-sm text-red-400">{errors.security_lockout_time}</p>
                                    )}
                                </div>
                                <div>
                                    <label htmlFor="security_session_timeout" className="block text-sm font-medium text-slate-300">
                                        Session Timeout (minutes)
                                    </label>
                                    <input
                                        id="security_session_timeout"
                                        type="number"
                                        min={1}
                                        max={1440}
                                        value={data.security_session_timeout}
                                        onChange={(e) => setData('security_session_timeout', Number(e.target.value))}
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 focus:border-emerald-500 focus:ring-emerald-500"
                                        required
                                    />
                                    {errors.security_session_timeout && (
                                        <p className="mt-2 text-sm text-red-400">{errors.security_session_timeout}</p>
                                    )}
                                </div>
                                <div>
                                    <label htmlFor="security_two_factor_threshold" className="block text-sm font-medium text-slate-300">
                                        2FA Threshold (minor currency units)
                                    </label>
                                    <input
                                        id="security_two_factor_threshold"
                                        type="number"
                                        min={0}
                                        value={data.security_two_factor_threshold}
                                        onChange={(e) =>
                                            setData('security_two_factor_threshold', Number(e.target.value))
                                        }
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 focus:border-emerald-500 focus:ring-emerald-500"
                                        required
                                    />
                                    {errors.security_two_factor_threshold && (
                                        <p className="mt-2 text-sm text-red-400">{errors.security_two_factor_threshold}</p>
                                    )}
                                </div>
                                <div>
                                    <label
                                        htmlFor="security_admin_approval_threshold"
                                        className="block text-sm font-medium text-slate-300"
                                    >
                                        Admin Approval Threshold (minor currency units)
                                    </label>
                                    <input
                                        id="security_admin_approval_threshold"
                                        type="number"
                                        min={0}
                                        value={data.security_admin_approval_threshold}
                                        onChange={(e) =>
                                            setData('security_admin_approval_threshold', Number(e.target.value))
                                        }
                                        className="mt-2 block w-full rounded-md border border-slate-700 bg-slate-950 text-slate-50 focus:border-emerald-500 focus:ring-emerald-500"
                                        required
                                    />
                                    {errors.security_admin_approval_threshold && (
                                        <p className="mt-2 text-sm text-red-400">
                                            {errors.security_admin_approval_threshold}
                                        </p>
                                    )}
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter className="flex justify-end">
                            <Button
                                type="submit"
                                disabled={processing}
                                className="bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-semibold"
                            >
                                {processing ? 'Saving...' : 'Save Changes'}
                            </Button>
                        </CardFooter>
                    </Card>
                </form>

                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">System Maintenance</CardTitle>
                        <CardDescription className="text-slate-400">
                            Database and cache management tools for administrators.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        <Button
                            onClick={handleClearCache}
                            variant="outline"
                            className="w-full justify-start bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50"
                        >
                            <Trash2 className="h-4 w-4 mr-2" />
                            Clear Application Cache
                        </Button>
                        <Button
                            onClick={handleRunMigrations}
                            variant="outline"
                            className="w-full justify-start bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50"
                        >
                            <Database className="h-4 w-4 mr-2" />
                            Run Database Migrations
                        </Button>
                        <a href="/admin/settings/backup-database">
                            <Button
                                variant="outline"
                                className="w-full justify-start bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50"
                            >
                                <Download className="h-4 w-4 mr-2" />
                                Download Database Backup
                            </Button>
                        </a>
                        <a href="/admin/settings/system-info">
                            <Button
                                variant="outline"
                                className="w-full justify-start bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50"
                            >
                                <Info className="h-4 w-4 mr-2" />
                                View System Information
                            </Button>
                        </a>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}
