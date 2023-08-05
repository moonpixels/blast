import dayjs from 'dayjs'
import localizedFormat from 'dayjs/plugin/localizedFormat'
import utc from 'dayjs/plugin/utc'

dayjs.extend(localizedFormat)
dayjs.extend(utc)

export default function useFormatDate(date: string, format = 'lll'): string {
  if (format === 'iso') {
    return dayjs(date).toISOString()
  }

  return dayjs(date).format(format)
}
