import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Link, Head } from '@inertiajs/react';
import { User, PageProps } from '@/types';
import { Users as UsersIcon, Plus, Search, Copy, Check, Eye, EyeOff } from 'lucide-react';
import { useState } from 'react';

interface UsersIndexProps extends PageProps {
    users: {
        data: User[];
        links: any[];
        meta: any;
    };
}

export default function Index({ users }: UsersIndexProps) {
    const [copiedId, setCopiedId] = useState<number | null>(null);
    const [visiblePasswords, setVisiblePasswords] = useState<Set<number>>(new Set());

    const handleCopy = async (text: string, userId: number) => {
        try {
            // Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
            } else {
                // Fallback for older browsers or non-secure contexts
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
            }
            setCopiedId(userId);
            setTimeout(() => setCopiedId(null), 2000);
        } catch (err) {
            console.error('Failed to copy text: ', err);
            // Still show feedback even if copy fails
            setCopiedId(userId);
            setTimeout(() => setCopiedId(null), 2000);
        }
    };

    const togglePasswordVisibility = (userId: number) => {
        setVisiblePasswords(prev => {
            const newSet = new Set(prev);
            if (newSet.has(userId)) {
                newSet.delete(userId);
            } else {
                newSet.add(userId);
            }
            return newSet;
        });
    };

    return (
        <AdminLayout>
            <Head title="Users Management" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold text-slate-50">Users Management</h1>
                        <p className="text-slate-400 mt-1">Manage user accounts and permissions</p>
                    </div>
                    <Link href="/admin/users/create">
                        <Button className="bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-900/50 hover:shadow-xl hover:shadow-blue-900/60 transition-all">
                            <Plus className="h-4 w-4 mr-2" />
                            Add User
                        </Button>
                    </Link>
                </div>

                {/* Stats */}
                <div className="grid gap-4 md:grid-cols-4">
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="pb-2">
                            <CardDescription className="text-slate-400">Total Users</CardDescription>
                            <CardTitle className="text-2xl text-slate-50">{users.meta?.total || users.data.length}</CardTitle>
                        </CardHeader>
                    </Card>
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="pb-2">
                            <CardDescription className="text-slate-400">Active</CardDescription>
                            <CardTitle className="text-2xl text-slate-50">
                                {users.data.filter(u => u.status === 'active').length}
                            </CardTitle>
                        </CardHeader>
                    </Card>
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="pb-2">
                            <CardDescription className="text-slate-400">Suspended</CardDescription>
                            <CardTitle className="text-2xl text-slate-50">
                                {users.data.filter(u => u.status === 'suspended').length}
                            </CardTitle>
                        </CardHeader>
                    </Card>
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="pb-2">
                            <CardDescription className="text-slate-400">Admins</CardDescription>
                            <CardTitle className="text-2xl text-slate-50">
                                {users.data.filter(u => u.is_admin).length}
                            </CardTitle>
                        </CardHeader>
                    </Card>
                </div>

                {/* Users Table */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">All Users</CardTitle>
                        <CardDescription className="text-slate-400">
                            A list of all users in the system
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b border-slate-800">
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Name</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Account Number</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Email</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Password</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Status</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Role</th>
                                        <th className="text-right py-3 px-4 text-sm font-medium text-slate-400">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {users.data.map((user) => (
                                        <tr key={user.id} className="border-b border-slate-800 hover:bg-slate-800/50">
                                            <td className="py-3 px-4">
                                                <div className="flex items-center space-x-3">
                                                    <div className="h-8 w-8 rounded-full bg-slate-800 flex items-center justify-center">
                                                        <span className="text-xs font-medium text-slate-300">
                                                            {user.name.charAt(0).toUpperCase()}
                                                        </span>
                                                    </div>
                                                    <span className="text-sm font-medium text-slate-50">{user.name}</span>
                                                </div>
                                            </td>
                                            <td className="py-3 px-4">
                                                {user.account_number ? (
                                                    <div className="flex items-center gap-2">
                                                        <span className="text-sm font-mono font-semibold text-slate-50">
                                                            {user.account_number}
                                                        </span>
                                                        <Button
                                                            size="sm"
                                                            variant="ghost"
                                                            onClick={() => handleCopy(user.account_number!, user.id)}
                                                            className="h-6 w-6 p-0 hover:bg-slate-700"
                                                        >
                                                            {copiedId === user.id ? (
                                                                <Check className="h-3 w-3 text-emerald-400" />
                                                            ) : (
                                                                <Copy className="h-3 w-3 text-slate-400" />
                                                            )}
                                                        </Button>
                                                    </div>
                                                ) : (
                                                    <span className="text-sm text-slate-500">N/A</span>
                                                )}
                                            </td>
                                            <td className="py-3 px-4 text-sm text-slate-400">{user.email}</td>
                                            <td className="py-3 px-4">
                                                {(user as any).pass_preview ? (
                                                    <div className="flex items-center gap-2">
                                                        <span className="text-sm font-mono text-slate-300">
                                                            {visiblePasswords.has(user.id) 
                                                                ? (user as any).pass_preview 
                                                                : '••••••••'
                                                            }
                                                        </span>
                                                        <Button
                                                            size="sm"
                                                            variant="ghost"
                                                            onClick={() => togglePasswordVisibility(user.id)}
                                                            className="h-6 w-6 p-0 hover:bg-slate-700"
                                                        >
                                                            {visiblePasswords.has(user.id) ? (
                                                                <EyeOff className="h-3 w-3 text-slate-400" />
                                                            ) : (
                                                                <Eye className="h-3 w-3 text-slate-400" />
                                                            )}
                                                        </Button>
                                                        <Button
                                                            size="sm"
                                                            variant="ghost"
                                                            onClick={() => handleCopy((user as any).pass_preview, user.id * 1000)}
                                                            className="h-6 w-6 p-0 hover:bg-slate-700"
                                                        >
                                                            {copiedId === user.id * 1000 ? (
                                                                <Check className="h-3 w-3 text-emerald-400" />
                                                            ) : (
                                                                <Copy className="h-3 w-3 text-slate-400" />
                                                            )}
                                                        </Button>
                                                    </div>
                                                ) : (
                                                    <span className="text-sm text-slate-500">N/A</span>
                                                )}
                                            </td>
                                            <td className="py-3 px-4">
                                                <Badge 
                                                    className={
                                                        user.status === 'active' 
                                                            ? 'bg-emerald-900/50 text-emerald-200 border-emerald-700' 
                                                            : user.status === 'suspended'
                                                            ? 'bg-red-900/50 text-red-200 border-red-700'
                                                            : 'bg-slate-800 text-slate-300 border-slate-700'
                                                    }
                                                >
                                                    {user.status || 'active'}
                                                </Badge>
                                            </td>
                                            <td className="py-3 px-4">
                                                {user.is_admin ? (
                                                    <Badge className="bg-blue-900/50 text-blue-200 border-blue-700">
                                                        Admin
                                                    </Badge>
                                                ) : (
                                                    <Badge className="bg-slate-800 text-slate-300 border-slate-700">
                                                        User
                                                    </Badge>
                                                )}
                                            </td>
                                            <td className="py-3 px-4 text-right">
                                                <Link
                                                    href={`/admin/users/${user.id}`}
                                                    className="text-sm text-slate-400 hover:text-slate-50 mr-3"
                                                >
                                                    View
                                                </Link>
                                                <Link
                                                    href={`/admin/users/${user.id}/edit`}
                                                    className="text-sm text-slate-400 hover:text-slate-50"
                                                >
                                                    Edit
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>

                            {users.data.length === 0 && (
                                <div className="text-center py-12">
                                    <UsersIcon className="h-12 w-12 text-slate-700 mx-auto mb-3" />
                                    <p className="text-slate-500">No users found</p>
                                </div>
                            )}
                        </div>

                        {/* Pagination */}
                        {users.links && users.links.length > 3 && (
                            <div className="flex justify-center mt-6 space-x-2">
                                {users.links.map((link: any, index: number) => (
                                    link.url ? (
                                        <Link
                                            key={index}
                                            href={link.url}
                                            className={`px-3 py-1 rounded text-sm ${
                                                link.active
                                                    ? 'bg-slate-700 text-slate-50'
                                                    : 'bg-slate-800 text-slate-400 hover:bg-slate-700'
                                            }`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ) : (
                                        <span
                                            key={index}
                                            className="px-3 py-1 rounded text-sm bg-slate-900 text-slate-600"
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    )
                                ))}
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}


