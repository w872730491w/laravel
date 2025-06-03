interface Permission {
    id: number | null
    pid: number | null
    type: 0 | 1
    name: string | null
    display_name: string | null
    status: boolean
}

interface Design {
    id: number
    name: string
    type: string
}
